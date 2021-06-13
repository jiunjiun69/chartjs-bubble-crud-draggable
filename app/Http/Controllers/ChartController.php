<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use App\bubble;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function chart()
    {
        return view('chart');
    }

    public function chart1()
    {
        return view('chart1');
    }

    public function bubble()
    {
        $bubbles = bubble::all();
        // dd(count($bubbles));
        // dd($bubbles);

        if(count($bubbles)==0){

            // $bubbles->data = [{'x':15.03,'y':74.58,'r':10}];
            DB::insert('insert into bubbles (label, data, borderWidth, backgroundColor, pointHitRadius, animations) values (?, ?, ?, ?, ?, ?)', ['噴灌1', '[{"x":30.4,"y":51.16,"r":10}]', 1, 'rgb(189, 80, 105, 1)', 25, '{"numbers":[],"colors":[]}']);
            // $bubbles->data = [{"x":5.495970507544581,"y":39.15380658436214,"r":10}];
            // $bubbles->data = [];
            // return view('bubble')->with('bubbles',[]);
            // dd('dfd');
        }
        $bubbles = bubble::all();
        // $array = '';
        // $array1 =
        // $i = 0;
        // foreach ($bubbles as $bubble) {
        //     if($i == 0){
        //         $array = $array.$bubble->data;
        //     }else{
        //         $array = $array.','.$bubble->data;
        //     }
        //     $i++;
        // }
        // $array = '['.$array.']';


        // dd($bubbles);
        // dd($array);
        // dd($bubbles->data);
        // dd($bubbles->data);
        // $bubbles_replace = str_replace('"', '', $bubbles->data);
        // dd($bubbles_replace);
        // [{"label":"a","data":[{"x":49.63,"y":62.53,"r":10}],"borderWidth":1,"backgroundColor":"rgb(189, 80, 105, 1)","pointHitRadius":25,"animations":{"numbers":{},"colors":{}}},{"label":"b","data":[{"x":12.57,"y":34.01,"r":10}],"borderWidth":1,"backgroundColor":"rgb(189, 80, 105, 1)","pointHitRadius":25,"animations":{"numbers":{},"colors":{}}}]
        // "[{"label":"a","data":[{"x":49.63,"y":62.53,"r":10}],"borderWidth":1,"backgroundColor":"rgb(189, 80, 105, 1)","pointHitRadius":25,"animations":{"numbers":[],"colors":[]}},{"label":"b","data":[{"x":12.57,"y":34.01,"r":10}],"borderWidth":1,"backgroundColor":"rgb(189, 80, 105, 1)","pointHitRadius":25,"animations":{"numbers":[],"colors":[]}}]"
        return view('bubble')->with('bubbles',$bubbles);
    }

    public function save(Request $data)
    {
        // save
        // dd($data->all());

        // DB::connection('mysql');

        // $bubbles = DB::table('bubbles')->get();
        // dd($bubbles);



        // 清除資料表以進行資料刷新
        DB::table('bubbles')->truncate();
        // DB::delete('delete from bubbles');

        $arrays = json_decode($data->save, true);
        // dd($arrays[3]['data']);
        // dd($arrays[3]['data'] == []);

        // 偵測chart以shift清除的資料為空值的data屬性將整筆資料從資料庫中刪除
        for ( $i=0 ; $i < count($arrays) ; $i++ ){
            if($arrays[$i]['data'] == []){
                // dd($arrays);
                unset($arrays[$i]);
                // dd($arrays);
                $arrays = array_values($arrays);
                // $deleted = DB::delete('delete from bubbles where id = ?', [$bubbles[$i]->id]);


            }
        }

        // bubble::create(['data' => '[{"x":5.495970507544581,"y":39.15380658436214,"r":10}][{"x":5.495970507544581,"y":39.15380658436214,"r":10}]']);

        // foreach ($arrays as $array) {
        //     bubble::create([
        //         'name' => 'aa',
        //         'data' => json_encode($array)
        //         ]);

        //     // dd(bubble::create(['data' => $array]));
        //     // bubble::create(['data' => $array]);
        // }
        foreach ($arrays as $array) {
            // bubble::create([
            //     'name' => 'aa',
            //     'data' => json_encode($array)
            //     ]);
            // dd($array['label']);
            DB::insert('insert into bubbles (label, data, borderWidth, backgroundColor, pointHitRadius, animations) values (?, ?, ?, ?, ?, ?)', [json_encode($array['label']), json_encode($array['data']), json_encode($array['borderWidth']), json_encode($array['backgroundColor']), json_encode($array['pointHitRadius']), json_encode($array['animations'])]);


            // dd(bubble::create(['data' => $array]));
            // bubble::create(['data' => $array]);
            }

        // // 偵測chart以shift清除的資料為空值的data屬性將整筆資料從資料庫中刪除
        // $bubbles = bubble::all();
        // for ( $i=0 ; $i < count($bubbles) ; $i++ ){
        //     if($bubbles[$i]->data == '[]'){
        //         $deleted = DB::delete('delete from bubbles where id = ?', [$bubbles[$i]->id]);

        //     }
        // }
        // // dd($bubbles[4]->data == '[]');
        // // $bubbles[3]->data == '[]';


        // dd($array);
        // $bubbles = bubble::find(1);
        // $bubbles->data = $data->save;

        // dd($data->save);
        // $bubbles = new bubble;
        // $bubbles->data = json_encode($data->save);

        // $bubbles->save();

        return redirect()->route('bubble')->withErrors(['msg' =>'Save Success!']);
    }

}
