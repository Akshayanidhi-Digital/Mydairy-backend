<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pakeage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stevebauman\Location\Facades\Location;

class PlansController extends Controller
{
    public function index()
    {
        $title = "Plans List";
        $packages = Pakeage::paginate(env('PER_PAGE_RECORDS'));
        return view('admin.plans.index',compact('title','packages'));
    }
    public function create()
    {
        $title = "Plan create";
        return view('admin.plans.create',compact('title'));
    }
    public function store(Request $request){
        $request->validate([
            'plan_name'=>['required','string',Rule::unique('pakeages','name')],
            'category'=>['required','in:single,multiple'],
            'user_allowed'=>['required_if:category,multiple','numeric','min:0',],
            'farmer_count'=>['required','numeric','min:0',],
            'price'=>['required','numeric','min:0',],
            'duration'=>['required','numeric','min:0',],
            'duration_type'=>['required','in:day,month,year'],
            'description'=>['required','min:150','max:220'],
        ]);
        $plan = new Pakeage();
        $plan->name = $request->plan_name;
        $plan->category = $request->category;
        $plan->user_count = ($request->user_allowed) ? $request->user_allowed : 0;
        $plan->farmer_count =  $request->farmer_count;
        $plan->price =  $request->price;
        $plan->duration =  $request->duration;
        $plan->duration_type =  $request->duration_type;
        $plan->description =  $request->description;
        if($plan->save()){
            return redirect()->route('admin.plans.list')->with('success','New plan created successfully.');
        }else{
            return redirect()->back()->with('error','Somthing went wrong.');
        }
    }
    public function edit($id){
        $title = "Plan edit";
        $plan = Pakeage::find($id);
        return view('admin.plans.edit',compact('title','plan'));
    }
    public function update(Request $request,$id){
        $plan = Pakeage::findorfail($id);
        $request->validate([
            'plan_name'=>['required','string',Rule::unique('pakeages','name')->ignore($id)],
            'category'=>['required','in:single,multiple'],
            'user_allowed'=>['required_if:category,multiple','numeric','min:0',],
            'farmer_count'=>['required','numeric','min:0',],
            'price'=>['required','numeric','min:0',],
            'duration'=>['required','numeric','min:0',],
            'duration_type'=>['required','in:day,month,year'],
            'description'=>['required','min:150','max:220'],
        ]);
        $plan->name = $request->plan_name;
        $plan->category = $request->category;
        $plan->user_count = ($request->user_allowed) ? $request->user_allowed : 0;
        $plan->farmer_count =  $request->farmer_count;
        $plan->price =  $request->price;
        $plan->duration =  $request->duration;
        $plan->duration_type =  $request->duration_type;
        $plan->description =  $request->description;
        if($plan->save()){
            return redirect()->route('admin.plans.list')->with('success','plan updated successfully.');
        }else{
            return redirect()->back()->with('error','Somthing went wrong.');
        }
    }
}
