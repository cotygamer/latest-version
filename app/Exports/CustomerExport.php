<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [];
       
        if(\Auth::user()->type =='company')
        {
            $data = Customer::where('created_by', \Auth::user()->id)->get();
        }
        else{
            $data = Customer::get();
        } 

        if (!empty($data)) {
            foreach ($data as $k => $customer) {
                unset($customer->id, $customer->avatar, $customer->is_active, $customer->password, $customer->created_at, $customer->updated_at, $customer->lang, $customer->created_by, $customer->email_verified_at, $customer->remember_token);
                $data[$k]["customer_id"] = \Auth::user()->customerNumberFormat($customer->customer_id);
                $data[$k]["balance"]     = \Auth::user()->priceFormat($customer->balance);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Id",
            "Customer No",
            "Name",
            "Email",
            "tax_number",
            "Contact",
            "Avatar",
            "Is Active",
            "Billing Name",
            "Billing Country",
            "Billing State",
            "Billing City",
            "Billing Phone",
            "Billing Zip",
            "Billing Address",
            "Shipping Name",
            "Shipping Country",
            "Shipping State",
            "Shipping City",
            "Shipping Phone",
            "Shipping Zip",
            "Shipping Address",
            "Balance",

        ];
    }
}
