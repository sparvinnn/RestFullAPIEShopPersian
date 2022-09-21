<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\CategoryProperty;
use App\Models\Color;
use App\Models\Design;
use App\Models\Material;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductCategoryField;
use App\Models\ProductProperty;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class ProductController extends Controller
{
    private $tables = [
        [
            "en" => "size",
            "fa" => 'اندازه',
            "table" => 'sizes'
        ],
        
        [
            "en" => "color",
            "fa" => "رنگ",
            "table" => 'colors'
        ]
      
    ];
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs     = $request->product;
            $product    = Product::create([
                "name"=> $inputs->name,
                "price"=> $inputs->price,
                "description"=> $inputs->description,
                "category_id"=> $inputs->category_id,
                "branch_id"=> $inputs->branch_id,
                "inventory_number"=> $inputs->inventory_number,
                "total_number"=> $inputs->total_number,
                "sales_number"=> $inputs->sales_number,
                "rate"=> $inputs->rate,
                "vote"=> $inputs->vote
            ]);
            DB::commit();
            return response()->json([
                "status"  => "success", 
                "message" => "با موفقیت افزوده شد", 
                "product" => $product],
            200);

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $e], 500);
        }


    }

    public function productProperties(Request $request){
        try{
            DB::beginTransaction();
            ProductProperty::query()->create([
                'product_id'=> $request->product_id,
                'branch_id' => $request->branch_id,
                'size' => $request->size?? null,
                'material'=> $request->material?? null,
                'color'=> $request->color?? null,
                'design'=> $request->design?? null,
                'sleeve'=> $request->sleeve?? null,
                'piece'=> $request->piece?? null,
                'set_type'=> $request->set_type?? null,
                'description'=> $request->description?? null,
                'maintenance'=> $request->maintenance?? null,
                'made_in'=> $request->made_in?? null,
                'origin'=> $request->origin?? null,
                'type'=> $request->type?? null,
                'for_use'=> $request->for_use?? null,
                'collar'=> $request->collar?? null,
                'height'=> $request->height?? null,
                'physical_feature'=> $request->physical_feature?? null,
                'production_time'=> $request->production_time?? null,
                'demension'=> $request->demension?? null,
                'crotch'=> $request->crotch?? null,
                'close'=> $request->close?? null,
                'drop'=> $request->drop?? null,
                'cumin'=> $request->cumin?? null,
                'close_shoes'=> $request->close_shoes?? null,
                'typeـofـclothing'=> $request->typeـofـclothing?? null,
                'specialized_features'=> $request->specialized_features?? null
            ]);
            DB::commit();
            return response()->json([
                "status" => "success", 
                "message" => "با موفقیت افزوده شد", 
            ],200);
        }catch(\Exception $exception){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $exception],500);
        }
    }

    public function update(Request $request, $id){
        try{
            DB::beginTransaction();
            $inputs = $request;
            $product   =   Product::find($id);
            if($inputs['name']) $product->name = $inputs['name'];
            if($inputs['price']) $product->price = $inputs['price'];
            if($inputs['description']) $product->description = $inputs['description'];
            if($inputs['category_id']) $product->category_id = $inputs['category_id'];
            if($inputs['branch_id']) $product->branch_id = $inputs['branch_id'];
            if($inputs['inventory_number']) $product->inventory_number = $inputs['inventory_number'];
            if($inputs['total_number']) $product->total_number = $inputs['total_number'];
            if($inputs['sales_number']) $product->sales_number = $inputs['sales_number'];
            if($inputs['brand_id']) $product->brand_id = $inputs['brand_id'];
            $product->save();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! registration completed", "product" => $product]);

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $e]);
        }
    }

    public function search(Request $request){
// return $request;
        $name = $request->name;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        $available = $request->available;
        // try{
            
            $list = Product::query()
                ->join('media', 'media.product_id', 'products.id')
                ->when($name, function ($q, $name){
                    return $q->where('products.name', 'LIKE', '%'.$name.'%');
                })
                ->when($min_price, function ($q, $min_price) {
                    return $q->where('products.sell_price', '>=', $min_price);
                })
                ->when($max_price, function ($q, $max_price) {
                    return $q->where('products.sell_price', '<=', $max_price);
                })
                ->when($category_id, function ($q, $category_id) {
                    return $q->where('products.category_id', $category_id);
                })
                ->when($branch_id, function ($q, $branch_id) {
                    return $q->where('products.branch_id', $branch_id);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('products.id', $id);
                })
                ->orderBy('products.updated_at', 'desc')
                ->limit(200)
                ->get();
            if ($available == 1)
                $list->where('inventory_number', '>', 0);
            else if ($available === 0)
                $list->where('inventory_number', '=', 0);

            $data = array();
            $i = 0;

            foreach ($list as $item){
                $property_keys = CategoryProperty::where('category_id', 9)
                    ->first();

                $category = Category::where('id', $item->category_id)->select('id','name_fa')->first();
                    
                if($property_keys){
                    $size = $property_keys->size? ProductProperty::query()
                    ->where('product_id', $item->id)
                    ->whereNotNull('size_id')
                    ->pluck('size_id'): null;
                    if($size) $size_list = Size::whereIn('id', $size)->pluck('name');

                    $material = $property_keys->material? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('material_id')
                        ->pluck('material_id'): null;
                    if($material) $material_list = Material::whereIn('id', $material)->pluck('name');

                    $color = $property_keys->color? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('color_id')
                        ->pluck('color_id'): null;
                    if($color) $color_list = Color::whereIn('id', $color)->pluck('name');

                    $design = $property_keys->design? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('design_id')
                        ->pluck('design_id'): null;
                    if($design) $design_list = Design::whereIn('id', $design)->pluck('name');

                    // $sleeve = $property_keys->sleeve? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('sleeve')
                    //     ->pluck('sleeve'): null;
                
                    // $piece = $property_keys->piece? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('piece')
                    //     ->pluck('piece'): null;

                    // $set_type = $property_keys->set_type? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('set_type')
                    //     ->pluck('set_type'): null;
                        
                    // $description = $property_keys->description? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('description')
                    //     ->pluck('description'): null;

                    // $maintenance = $property_keys->maintenance? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('maintenance')
                    //     ->pluck('maintenance'): null;

                    // $made_in = $property_keys->made_in? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('made_in')
                    //     ->pluck('made_in'): null;
 
                    // $origin = $property_keys->origin? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('origin')
                    //     ->pluck('origin'): null;

                    // $type = $property_keys->type? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('type')
                    //     ->pluck('type'): null;

                    // $for_use = $property_keys->for_use? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('for_use')
                    //     ->pluck('for_use'): null;

                    // $collar = $property_keys->collar? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('collar')
                    //     ->pluck('collar'): null;

                    // $height = $property_keys->height? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('height')
                    //     ->pluck('height'): null;

                    // $physical_feature = $property_keys->physical_feature? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('physical_feature')
                    //     ->pluck('physical_feature'): null;

                    // $production_time = $property_keys->production_time? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('production_time')
                    //     ->pluck('production_time'): null;

                    // $demension = $property_keys->demension? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('demension')
                    //     ->pluck('demension'): null;

                    // $crotch = $property_keys->crotch? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('crotch')
                    //     ->pluck('crotch'): null;

                    // $close = $property_keys->close? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('close')
                    //     ->pluck('close'): null;

                    // $drop = $property_keys->drop? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('drop')
                    //     ->pluck('drop'): null;

                    // $cumin = $property_keys->cumin? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('cumin')
                    //     ->pluck('cumin'): null;

                    // $close_shoes = $property_keys->close_shoes? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('close_shoes')
                    //     ->pluck('close_shoes'): null;

                    // $typeـofـclothing = $property_keys->typeـofـclothing? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('typeـofـclothing')
                    //     ->pluck('typeـofـclothing'): null;

                    // $specialized_features = $property_keys->specialized_features? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('specialized_features')
                    //     ->pluck('specialized_features'): null;

                    // $sell_price = $property_keys? ProductProperty::query()
                    //     ->where('product_id', $item->id)
                    //     ->whereNotNull('sell_price')
                    //     ->pluck('sell_price'): null;

                }else{
                    $size = null;
                    $design = null;
                    $description = null;
                    $maintenance = null;
                    $made_in = null;
                    $origin = null;
                    $type = null;
                    $for_use = null;
                    $collar = null;
                    $height = null;
                    $physical_feature = null;
                    $production_time = null;
                    $demension = null;
                    $crotch = null;
                    $close = null;
                    $drop = null;
                    $cumin = null;
                    $close_shoes = null;
                    $typeـofـclothing = null;
                    $specialized_features = null;
                    $sell_price = null;
                }
                


                $fields = ProductCategoryField::where('product_id', $item->id)
                    ->leftJoin('category_fields', 'category_fields.id', 'product_category_fields.category_field_id')
                    ->leftJoin('fields', 'fields.id', 'category_fields.field_id')
                    ->select([
                        'product_category_fields.product_id',
                        'product_category_fields.data',
                        'fields.id as field_id',
                        'fields.name'
                    ])
                    ->get();
                // $properties = ProductProperty::query()
                //     ->where('product_id', $item->id)
                    // ->when($properties_filter, function ($q, $properties_filter) {
                    //     return $q->whereIn('product_properties.property_id', $properties_filter);
                    // })
                    // ->join('category_metas', 'category_metas.id', 'product_properties.property_id')
                    // ->select([
                    //     'category_metas.value as key',
                    //     'product_properties.value as value',
                    // ])
                    // ->get();

                $images = Media::query()->where('product_id', $item->id)
                    ->select('id','url')->get();

                $data[$i++] = array([
                    'product' => $item,
                    'properties' => [
                        'size' => $size_list,
                        'color' => $color_list,
                    ],
                    'fields' => $fields,
                    'category' => $category,
                    'images' => $images,
                    'discount' => []
                ]);
            }

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $data
            ];

            return response()->json($response);
        // }catch(Exception $e){
        //     return response($e, 500);
        // }
    }

    public function product_properties(Request $request){
        foreach($request->tables as $item){
            if($item['value']!=''){
                $id = DB::table($item['table'])->where('id', $item['value'])->first();

                if($id){ 
                    $id = $id->id;
                    $input[$item['en'].'_id'] = $item['value'];
                }

            }
                
        }

        $input['product_id'] = $request->branch_id?? null;
        $input['product_id'] = $request->product_id;
        $input['sell_price'] = $request->sell_price;
        $input['is_active'] = $request->is_active;

        $data =ProductProperty::create($input);

        return response()->json([
            "success"  => true, 
            "message" => "با موفقیت افزوده شد", 
            "product" => $data
        ], 200);
        
    }

    public function get_properties($id){

        $properties = ProductProperty::where('product_id', $id)->get();

        $data = [];
        foreach($properties as $value){
            $temp = [];
            foreach($this->tables as $item){
                if($value[$item['en'].'_id']){
                    // return $item['table'];
                    $name = DB::table($item['table'])->where('id', $value[$item['en'].'_id'])->first();
                    if($name) $temp[$item['en']] = $name->name;
                }
            }
            $temp['sell_price'] = $value->sell_price;
            $temp['is_active'] = $value->is_active;
            $temp['branch_id'] = $value->brnach_id;
            $temp['id'] = $value->id;
            array_push($data, $temp);
        }

        return $data;
        

    }

    public function delete($id){
        try{
            $product = Product::find($id);
            $product->delete();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            return response()->json(['data'=>'error'], 500);
        }
    }
}
