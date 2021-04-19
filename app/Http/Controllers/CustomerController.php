<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers=Customer::orderBy('id','ASC')->paginate(10);
        return view('backend.customers.index')->with('customers',$customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
        [
            'name'=>'string|required|max:30',
            'email'=>'string|required|unique:users',
            'password'=>'string|required',
            'mobile'=>'nullable|string',
            'status'=>'required|in:active,inactive',
            'photo'=>'nullable|string',
        ]);
        // dd($request->all());
        $data=$request->all();
        $data['password']=Hash::make($request->password);
        // dd($data);
        $status=Customer::create($data);
        // dd($status);
        if($status){
            request()->session()->flash('success','Successfully added customer');
        }
        else{
            request()->session()->flash('error','Error occurred while adding customer');
        }
        return redirect()->route('customer.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer=Customer::findOrFail($id);
        return view('backend.customers.edit')->with('customer',$customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer=Customer::findOrFail($id);
        $this->validate($request,
        [
            'name'=>'string|required|max:30',
            'email'=>'string|required',
            'mobile'=>'nullable|string',
            'status'=>'required|in:active,inactive',
            'photo'=>'nullable|string',
        ]);
        // dd($request->all());
        $data=$request->all();
        // dd($data);
        
        $status=$customer->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated');
        }
        else{
            request()->session()->flash('error','Error occured while updating');
        }
        return redirect()->route('customers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete=Customer::findorFail($id);
        $status=$delete->delete();
        if($status){
            request()->session()->flash('success','Customer Successfully deleted');
        }
        else{
            request()->session()->flash('error','There is an error while deleting customers');
        }
        return redirect()->route('customers.index');
    }
}
