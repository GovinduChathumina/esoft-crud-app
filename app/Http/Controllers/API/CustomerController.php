<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Customer;
use Validator;
use App\Http\Resources\Customer as CustomerResource;
   
class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
    
        return $this->sendResponse(CustomerResource::collection($customers), 'Customers retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = $request->isMethod('put') ? Customer::findOrFail($request->id) : new Customer;

        $customer->customer_name = $request->input('customer_name');
        $customer->customer_age = $request->input('customer_age');
        $customer->customer_address = $request->input('customer_address');
        $customer->problem_description = $request->input('problem_description');
        $customer->date = $request->input('date');
        $customer->problem_status = $request->input('problem_status');

        if($customer->save()) {
            return $this->sendResponse(new CustomerResource($customer), 'Customer created successfully.');
        }
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
  
        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }
   
        return $this->sendResponse(new CustomerResource($customer), 'Customer retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'customer_name' => 'required',
            'customer_age' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $customer->name = $input['customer_name'];
        $customer->customer_age = $input['customer_age'];
        $customer->customer_address = $input['customer_address'];
        $customer->problem_description = $input['problem_description'];
        $customer->date = $input['date'];
        $customer->problem_status = $input['problem_status'];
        $customer->save();
   
        return $this->sendResponse(new CustomertResource($customer), 'Customer updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
   
        return $this->sendResponse([], 'Customer deleted successfully.');
    }
}