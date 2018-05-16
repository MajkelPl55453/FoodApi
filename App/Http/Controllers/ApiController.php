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
        $recipeDb = DB::select("SELECT * FROM przepisy WHERE id = :id", ['id' => $id])[0];

        $recipe['id'] = $recipeDb->id;
        $recipe['name'] = $recipeDb->nazwa;
        $recipe['image'] = $recipeDb->zdjecie;
        $recipe['time'] = $recipeDb->czas_przygotowania;
        $recipe['difficulty'] = $recipeDb->trudnosc;
        $recipe['portions'] = $recipeDb->ilosc_porcji;
        $recipe['category'] = $this->getCategoryPath($recipeDb->kategoria);
        $recipe['products'] = $this->getProductsForRecipe($id);
        $recipe['steps'] = $this->getStepsForRecipe($id);
        
        return response()->json(['recipe' => $recipe]);
    }
    
    public function postLogin(Request $request)
    {
        $data = $request->all();
        $data['login'] = Filter::getString($data['login']);
        $data['password'] = $this->hashPassword(Filter::getString($data['password']));
        
        $user = DB::select("SELECT * FROM uzytkownicy WHERE id = :id", ['id' => $id]);
        
        if(empty($user))
        {
            return response()->json(['status' => 'error', 'message' => 'Brak użytkownika o takim loginie!']);
        }
        
        if($user['haslo'] === $data['password'])
        {
            return response()->json(['status' => 'success', 'message' => 'Poprawnie zalogowano!']);
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => 'Błędne hasło']);
        }
        
        return response()->json(['status' => 'error', 'message' => 'error']);
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
    
    private function getProductsForRecipe($id)
    {
        return DB::select("SELECT p.*, pp.* FROM produkty p LEFT JOIN przepisy_produkty pp ON pp.id_produktu = p.id WHERE pp.id_przepisu = :id", ['id' => $id]);
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
