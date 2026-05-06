<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use Str, DB;

class AdminMenusBuilderController extends Controller
{
    public function index(Menu $menu)
    {     
      $menu->load(['parent_items.children']);
      return view('admin.menus.builder', compact(['menu']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Menu $menu, Request $request)
    {        
        $data = $request->validate([
                'title' => 'required',
                'url' => 'required'
              ]);
              
        if($data){

          $order = 1;
          $item = MenuItem::where('menu_id', '=', $menu->id)
                          ->where('parent_id', '=', null)
                          ->orderBy('order', 'DESC')
                          ->first();

          if (!is_null($item)) {
              $order = intval($item->order) + 1;
          }

          \DB::beginTransaction();

          try {
            $newItem = MenuItem::create([
                                  'menu_id' => $menu->id,
                                  'title' => $request->title,
                                  'url' => $request->url,
                                  'target' => $request->target,
                                  'icon_class' => $request->icon_class,
                                  'order' => $order,
                                  'controller' => $request->controller_name,
                                  'var_name' => $request->var_name,
                                  'active' => true,
                                ]);

            if($request->url != '#'){
              $controllerName = $request->controller_name 
                                  ?? $this->getControllerName($request->url);
              $permissionName = $this->getPermissionName($request->url);
              $groupName = $this->getGroupName($request->url);
  
              if($request->controller == 'on'){
                $newItem->update(['controller' => $controllerName]);

                Artisan::call('make:controller', [
                  'name' => $controllerName.'Controller',
                  '--resource' => true,
                ]);
    
                $this->createPermissions($permissionName, $groupName);

                $newItem->update(['permission' => 'open_'. $permissionName]);

              } elseif($request->permission == 'on'){
                $newItem->update(['permission' => 'open_'. $permissionName]);
                $permission = Permission::firstOrCreate([
                                          'name' => 'open_'.$permissionName,
                                          'guard_name' => 'web',
                                          'group' => $groupName
                                        ]);
              }
  
              if($request->var_name == ''){
                $var_name = Str::singular(Str::replace(['-',' '],'_', Str::lower($request->title)));
  
                $newItem->update(['var_name' => $var_name]);
              }

              \DB::commit();
            }

            if(\Arr::first(explode('.', request()->getHost())) != 'localhost'){
              Artisan::call('optimize');
            }
  
            if(\Cache::has($menu->name)){
              \Cache::forget($menu->name);
            }

            DB::commit();

            return redirect()->route('admin.menus.builder', ['menu' => $menu->id])
                          ->with('sukses', 'Create new Item Success.');

          } catch (\Throwable $th) {
            DB::rollback();

            return $th;
          }
        }        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Menu $menu, $id, Request $request)
    {
        $data = $request->validate([
          'title' => 'required',
          'url' => 'required'
        ]);

        if($data){
          $item = MenuItem::findOrFail($id);

          DB::beginTransaction();

          try {
            if($item->url != '#'){
              $permisiLama = $this->getPermissionName($item->url);

              $this->deletePermissions($permisiLama);
            }          
            $item->update($request->except(['controller', 'permission', 'controller_name', 'active']));

            if($request->active == 'on'){
              $item->update(['active' => true]);
            } else {
              $item->update(['active' => false]);
            }

            if($request->url != '#'){

              $controllerName = $request->controller_name 
                                ?? $this->getControllerName($request->url);
              $permissionName = $this->getPermissionName($request->url);
              $groupName = $this->getGroupName($request->url);

              if($request->controller == 'on'){
              
                $item->controller = $controllerName;
                $item->save();
    
                Artisan::call('make:controller', [
                  'name' => $controllerName.'Controller',
                  '--resource' => true,
                ]);
                
                $this->deletePermissions($permisiLama);
    
                $this->createPermissions($permissionName, $groupName);
    
              } else {
                $item->controller = NULL;
                $item->save();
              }

              if($request->permission == 'on'){
              
                $item->permission = 'open_'.$permissionName;
                $item->save();
                $permission = Permission::updateOrCreate([
                                          'name' => 'open_'.$permissionName,
                                          'guard_name' => 'web',                                      
                                        ],[
                                          'group' => $groupName
                                        ]);
    
              } else {
                $item->permission = NULL;
                $item->save();
              }

              if($request->var_name == ''){
                $var_name = Str::singular(Str::replace(['-',' '],'_', Str::lower($request->title)));

                $item->update(['var_name' => $var_name]);
              }

            }

            if(subDomain() !== 'localhost'){
              Artisan::call('optimize');
            }

            if(\Cache::has($menu->name)){
              \Cache::forget($menu->name);
            }

            DB::commit();

            return redirect()->route('admin.menus.builder', ['menu' => $menu->id])
                          ->with('sukses', 'Edit Item Success');
          } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
          }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu, $id)
    {
        $menuItem = MenuItem::findOrFail($id);
        if(!$menuItem->children->isEmpty()){
          foreach($menuItem->children as $child){
            $group = Permission::where('name', $child->permission)->first()->group;
            $delPermit = Permission::where('group', $group)->delete();
            $child->delete();
          }
        }
        
        if($menuItem->permission != ''){
          $permit = Permission::where('name', $menuItem->permission)->first()->group;
          $del = Permission::where('group', $permit)->delete();
        }

        $menuItem->delete();

        if(subDomain() !== 'localhost'){
          Artisan::call('optimize');
        }

        if(\Cache::has($menu->name)){
          \Cache::forget($menu->name);
        }

        return redirect('/administrator/menus/'.$menu->id.'/builder')->with('sukses', 'Delete Menu Item Success.');
    }

    public function order_item(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->orderMenu($menuItemOrder, null);
    }

    private function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
            $item = MenuItem::with(['children', 'menu'])->findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if(\Cache::has($item->menu?->name)){
              \Cache::forget($item->menu?->name);
            }

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }

    private function getControllerName($name)
    {

      $naming = explode("/", $name);
      $count = count($naming);
      $controllerName = '';

      if($count > 2){
        for ($c=1; $c < $count; $c++) { 
          $controllerName .= Str::replace('-', '', Str::title($naming[$c]));
        }
      } else {
        $controllerName = Str::replace('-', '', Str::title($naming[1]));
      }

      return $controllerName;

    }

    private function getPermissionName($name)
    {
        $naming = explode("/", $name);
        $count = count($naming);
        $last = $count - 1;
        $permissionName = '';

        if($count > 2){          
          for ($p= 1; $p < $count; $p++) {            
            if($p < $last){
              $permissionName .= Str::replace('-','_',Str::lower($naming[$p])).'_';
              
            } else {              
              $permissionName .= Str::replace('-','_',Str::lower($naming[$p]));              
            }               
          }
        } else {
          $permissionName = Str::lower($naming[1]);
        }
        // dd($permissionName);
        return $permissionName;
    }

    private function getGroupName($name){
      $naming = explode("/", $name);
      $count = count($naming);
      $last = $count - 1;
      $groupName = '';

      if($count > 2){          
        for ($p= 1; $p < $count; $p++) {            
          if($p < $last){
            $groupName .= Str::title($naming[$p]).'_';
            
          } else {              
            $groupName .= Str::title($naming[$p]);              
          }               
        }
      } else {
        $groupName = Str::title($naming[1]);
      }

      return $groupName;
    }

    public function deletePermissions($name)
    {
      $array = collect(['open', 'view', 'create', 'edit', 'delete']);

      foreach ($array as $ar) {
        $permission = Permission::where('name', $ar."_".$name)->first();
        if($permission){
          $permission->roles()->detach();
          $permission->delete();
        }        
      }
    }

    private function createPermissions($name, $title)
    {
      $array = collect(['open', 'view', 'create', 'edit', 'delete']);

      foreach ($array as $ar) {
        Permission::firstOrCreate([
                    'name' => $ar.'_'.$name,
                    'guard_name' => 'web',
                    'group' => $title
                  ]);
      }      
    }
}
