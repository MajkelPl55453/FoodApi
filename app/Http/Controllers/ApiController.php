<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use app\Helpers\Filter;

class ApiController extends Controller
{
    public function getGetCategoryTree(){
        
        $db_categories = DB::select("SELECT * FROM kategorie ORDER BY root ASC");
        $categories = [];
        $categories_t = [];
        
        foreach($db_categories as $db_category)
        {
            if($db_category->root > 0)
            {
                $categories_t[(int)$db_category->root]['childs'][$db_category->id] = [
                    'id' => $db_category->id,
                    'name' => $db_category->nazwa,
                    'root' => $db_category->root,
                    'symbol' => $db_category->symbol,
                    'childs' => []
                ];
            }
            else
            {
                $categories_t[(int)$db_category->id] = [
                    'id' => $db_category->id,
                    'name' => $db_category->nazwa,
                    'root' => $db_category->root,
                    'symbol' => $db_category->symbol,
                    'childs' => []
                ];
            }
        }
        
        foreach($categories_t as $cat_t)
        {
            $t = [
                'id' => $cat_t['id'],
                'name' => $cat_t['name'],
                'root' => $cat_t['root'],
                'symbol' => $cat_t['symbol'],
                'childs' => []
            ];
            if(count($cat_t['childs']) > 0)
            {
                foreach($cat_t['childs'] as $ch)
                {
                    $t['childs'][] = $ch;
                }
            }
            
            $categories['categories'][] = $t;
        }
        
        return response()->json($categories);
    }
    
    public function getGetProductsList()
    {
        $products_t = DB::select("SELECT * FROM produkty ORDER BY nazwa ASC");
        $products = [];
        foreach($products_t as $product_t)
        {
            $products[] = [
                'id' => $product_t->id,
                'name' => $product_t->nazwa,
                'symbol' => $product_t->symbol
            ];
        }
        
        return response()->json(['products' => $products]);
    }
    
    public function getGetRecipe($id)
    {
        $id = Filter::getInt($id);
        $recipe = [];
        $recipeDb = DB::select("SELECT id, nazwa, czas_przygotowania, trudnosc, ilosc_porcji, kategoria FROM przepisy WHERE id = :id", ['id' => $id])[0];
        $recipe['id'] = $recipeDb->id;
        $recipe['name'] = $this->decode($recipeDb->nazwa);
        $recipe['image'] = 'http://www.foodapi.pl/images/'.$recipeDb->id.'.jpg';
        $recipe['time'] = $this->decode($recipeDb->czas_przygotowania);
        $recipe['difficulty'] = $this->decode($recipeDb->trudnosc);
        $recipe['portions'] = $this->decode($recipeDb->ilosc_porcji);
        $recipe['category'] = $this->getCategoryPath($recipeDb->kategoria);
        $recipe['products'] = $this->getProductsForRecipe($id);
        $recipe['steps'] = $this->getStepsForRecipe($id);
        
        return response()->json(['recipe' => $recipe]);
    }
    
    public function getGetRecipesNameList()
    {
        $recipesNamesDb = DB::select("SELECT id, nazwa FROM przepisy ORDER BY nazwa ASC");
        $names = [];
        
        foreach($recipesNamesDb as $recipeNameDb){
            $names[$this->decode($recipeNameDb->nazwa)] = [
                'id' => $recipeNameDb->id,
                'name' => $this->decode($recipeNameDb->nazwa)
            ];
        }
        
        return response()->json(['names' => array_values($names)]);
    }
    
    private function decode($s)
    {
        return trim(html_entity_decode(htmlspecialchars_decode($s)));
    }
    
    public function postLogin(Request $request)
    {
        $data = $request->all();
        $data['login'] = Filter::getString($data['login']);
        $data['password'] = $this->hashPassword(Filter::getString($data['password']));
        
        $user = DB::select("SELECT * FROM uzytkownicy WHERE login = :login", ['login' => $data['login']]);
        
        if(empty($user))
        {
            return response()->json(['status' => 'error', 'message' => 'Brak użytkownika o takim loginie!']);
        }
        
        if($user['haslo'] === $data['password'])
        {
            return response()->json(['status' => 'success', 'message' => 'Poprawnie zalogowano!', 'userid' => $user->id]);
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => 'Błędne hasło', 'userid' => 0]);
        }
        
        return response()->json(['status' => 'error', 'message' => 'error', 'userid' => 0]);
    }
    
    public function postRegister(Request $request)
    {
        $data = $request->all();
        $post = [];
        $post['login'] = Filter::getString($data['login']);
        $post['password'] = $this->hashPassword(Filter::getString($data['password']));
        $post['email'] = Filter::getString($data['email']);
        
        $db = DB::insert("INSERT INTO uzytkownicy (login, email, haslo) VALUES (:login, :password, :email)", $post);
        
        if($db)
        {
            return response()->json(['status' => 'success', 'message' => 'Zarejestrowano pomyślnie!']);
        }
        else {
            return response()->json(['status' => 'error', 'message' => 'Wystąpił błąd, prosimy spróbować ponownie za chwilę.']);
        }
    }
    
    public function getGetRecipesList($category = 0, $limit, $offset)
    {
        $params = ['limit' => $limit, 'offset' => $offset];
        $where = [];
        if($category > 0)
        {
            $params['kategoria'] = $category;
            $where['kategoria'] = 'kategoria IN (SELECT id FROM kategorie WHERE root = :kategoria OR id = :kategoria)';
        }
        
        $recipesDb = DB::select("SELECT id, nazwa, czas_przygotowania, trudnosc, ilosc_porcji, kategoria FROM przepisy " . (!empty($where) ? 'WHERE ' . implode(' AND ', $where) : '') . " LIMIT :limit OFFSET :offset", $params);
        $recipes = [];
        foreach($recipesDb as $recipeDb)
        {
            $recipes[] = [
                'id' => $recipeDb->id,
                'nazwa' => $recipeDb->nazwa,
                'czas_przygotowania' => $recipeDb->czas_przygotowania,
                'trudnosc' => $recipeDb->trudnosc,
                'ilosc_porcji' => $recipeDb->ilosc_porcji,
                'zdjecie' => 'http://www.foodapi.pl/images/'.$recipeDb->id.'.jpg',
                'kategoria' => $recipeDb->kategoria
            ];
        }
        
        return response()->json(['recipes' => $recipes]);
    }
    
    private function getProductsForRecipe($id)
    {
        $products = [];
        $productsDb = DB::select("SELECT p.*, pp.* FROM produkty p LEFT JOIN przepisy_produkty pp ON pp.id_produktu = p.id WHERE pp.id_przepisu = :id", ['id' => $id]);
        foreach($productsDb as $prodDb)
        {
            $products[$prodDb->id] = $prodDb;
        }
        return $products;
    }
    
    private function getStepsForRecipe($id)
    {
        return DB::select("SELECT id, opis FROM przepisy_kroki WHERE id_przepisu = :id", ['id' => $id]);
    }
    
    private function hashPassword($pw)
    {
        $default_hash = sha1("@l@ m@ k0t@ @ k0t m@ @le");
        return sha1($pw . $default_hash);
    }
    
    private function getCategoryPath($id)
    {
        $categoriesDb = DB::select("SELECT * FROM kategorie");
        $catTemp = [];
        foreach($categoriesDb as $catDb)
        {
            $catTemp[$catDb->id] = $catDb;
        }
        
        if((int)$catTemp[$id]->root > 0)
        {
            return [$catTemp[$catTemp[$id]->root],$catTemp[$id]];
        }
        else
        {
            return ['', $catTemp[$id]];
        }
    }
}
