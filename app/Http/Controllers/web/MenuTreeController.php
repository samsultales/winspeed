<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Menu;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class MenuTreeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getrolemenudata (Request $request)
    {
        $id = $_GET['id'];
		// $data = $this->RoleModel->getRoleMenuData($id);
		$data = DB::select("SELECT menu_id FROM role_menu WHERE role_id =".$id);
		header('Content-Type: application/json');
		// echo json_encode($data);
        return $data;

        //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Menu',
        //     'data' => $treemenu, 
        //     'role_menu_ids' => $role_menu
        // ], 200);
    }

    public function getrolemenu() {
		// $menurows = $this->db->from('MENU')->get()->result_array();
		$menurows = MENU::get();
		// if ($this->input->get('root')==1) {
		if ($_GET['root'] == 1) {
			$treemenu = [];
			$item = [];
			$item['title'] = 'Root';
			$item['key'] = '';
			$item['expanded'] = true;
			$item['folder'] = true;
			$item['children'] = $this->_treemenu($menurows);
			$item['level'] = 0;
			$item['text'] = 'Root';
			$treemenu[] = $item;
		} else {
			$treemenu = $this->_treemenu($menurows);
		}
		echo json_encode($treemenu, JSON_NUMERIC_CHECK);
	}

    private function _treemenu($arraymenu, $parent_id = 0, $level = 1)
    {
        $arrayresult = [];
        $filtered = $arraymenu->where('parent_id', $parent_id)->where('is_active', 1)->sortBy('sequence')->toArray();
        foreach ($filtered as $row) {
            $hasChildren = $arraymenu->where('parent_id', $row['id'])->count() > 0 ? true : false;
            $item = [];
            if (empty($row['icon'])) {
                if ($hasChildren) {
                    $icon = 'bx bx-folder';
                } else {
                    $icon = 'bx bx-radio-circle';
                }
            } else {
                $icon = $row['icon'];
            }
            $icon = '<i class="' . $icon . '"></i>&nbsp;';
            $item['title'] = $row['name'];
            $item['key'] = $row['id'];
            $item['expanded'] = $hasChildren;
            $item['folder'] = $hasChildren;
            $item['icon'] = $row['icon'];
            $item['route'] = $row['route'];
            $item['sequence'] = $row['sequence'];
            $item['is_active'] = $row['is_active'];
            $item['is_default'] = $row['is_default'];
            $item['level'] = $level;
            // $item['text'] = $row['name'];
            if ($hasChildren) {
                $item['children'] = $this->_treemenu($arraymenu, $row['id'], $level + 1);
            }
            $arrayresult[] = $item;

        }
        return $arrayresult;
    }

    private function _treemenuroot($arraymenu, $parent_id = 0, $level = 1)
    {
        $arrayresult = [];
        $filtered = $arraymenu->where('parent_id', $parent_id)->sortBy('sequence')->toArray();
        // if ($parent_id == null) {
        //     $filtered = $arraymenu;
        // } else {
        //     $filtered = $arraymenu->where('parent_id',$parent_id)->sortBy('sequence')->toArray();
        // }
        // // var_dump($filtered);
        // foreach ($filtered as $row) {
        //     $hasChildren = $arraymenu->where('parent_id',$row['id'])->count() > 0 ? true : false;
        //     $item = [];
        //     if (empty($row['icon'])) {
        //         if ($hasChildren) {
        //             $icon = 'bx bx-folder';
        //         } else {
        //             $icon = 'bx bx-radio-circle';
        //         }
        //     } else {
        //         $icon = $row['icon']; 
        //     }
        //     $icon = '<i class="'.$icon.'"></i>&nbsp;';
        //     $item['name'] = $row['name'];
        //     $item['id'] = $row['id'];
        //     $item['expanded'] = $hasChildren;
        //     // $item['folder'] = $hasChildren;
        //     $item['icon'] = $row['icon'];
        //     $item['route'] = $row['route'];
        //     $item['sequence'] = $row['sequence'];
        //     $item['is_active'] = $row['is_active'];
        //     $item['is_default'] = $row['is_default'];
        //     $item['level'] = $level;
        //     // $item['text'] = $row['name'];
        //     if ($hasChildren) {
        //         $item['children'] = $this->_treemenuroot($arraymenu, $row['id'], $level+1);
        //     }
        //     $arrayresult[] = $item;

        // }
        foreach ($filtered as $row) {
            $item = 'id' . $row['id'];
            $arrayresult[] = $item;
        }
        return $arrayresult;
    }

    public function setmenurole()
    {
        $role_id = $_POST['role_id'];
        $menu = json_decode($_POST['menu'], true);
        // $menu = json_decode($this->input->post('menu'), true);
        // $data = $this->RoleModel->setmenurole($role_id, $menu);
        DB::beginTransaction();
        try {
            $insertedRows = array();
            $menu_count = count($menu);
            // $this->db->where(['ROLE_ID' => $role_id])->delete('ROLE_MENU');
            if ($menu_count !== 0) {
                DB::table('role_menu')->where('role_id', $role_id)->delete();
               
                for ($i = 0; $i < $menu_count; $i++) {
                    $row = [
                        'role_id' => $role_id,
                        'menu_id' => $menu[$i],
                    ];
                    
                    // array_push($insertedRows, $row);
                    $insert[] = $row;
                };

                // var_dump($insert); exit;
                // $this->db->insert_batch("ROLE_MENU", $insertedRows);
                // $data = DB::insert("role_menu", $insert);
                $data = DB::table('role_menu')->insert($insert);
            }

            $response['success'] = true;
            DB::commit();
            // all good
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            DB::rollback();
        }
        header('Content-Type: application/json');
     
        return response()->json([
            'success' => true,
            'message' => 'Menu Role Succefuly Setup',  
        ], 200);
    }
}
