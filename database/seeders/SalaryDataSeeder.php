<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Salary;
use App\Models\User;
 
class SalaryDataSeeder extends Seeder
{
    public function run()
    {
        $month = '2026-05';
        
        $data = [
            ['name' => 'Md. Abul Hasan Saidy', 'amount' => 60000, 'account' => '3781-101-83523'],
            ['name' => 'Mohammad Faisal', 'amount' => 60000, 'account' => '3781-101-83725'],
            ['name' => 'Insan Kamal Shafat', 'amount' => 40000, 'account' => '3781-101-83764'],
            ['name' => 'Sakib Hasan', 'amount' => 46000, 'account' => '3781-101-83536'],
            ['name' => 'Lutfur Kabir Rana', 'amount' => 40000, 'account' => '3781-101-83501'],
            ['name' => 'Sharafat Ullah Mohim', 'amount' => 40000, 'account' => '3781-101-83756'],
            ['name' => 'Mainul Hasan', 'amount' => 40000, 'account' => '3781-101-83609'],
            ['name' => 'Singmay Chowdhury', 'amount' => 30000, 'account' => '3781-101-83540'],
            ['name' => 'Arif Hossain Nayan', 'amount' => 17000, 'account' => '3781-101-83710'],
            ['name' => 'Abdul Alim Shezan', 'amount' => 32000, 'account' => '3781-101-83586'],
            ['name' => 'Moshraful Islam', 'amount' => 30000, 'account' => '3781-101-83560'],
            ['name' => 'Harunur Rashid', 'amount' => 15000, 'account' => '3781-101-83684'],
            ['name' => 'Abu haider', 'amount' => 13000, 'account' => '3781-101-83783'],
            ['name' => 'Anta Tasnim Rafa', 'amount' => 17500, 'account' => '3781-101-83555'],
            ['name' => 'Kawcer Hossen Rakib', 'amount' => 25000, 'account' => '3781-101-83497'],
            ['name' => 'Md Shohan', 'amount' => 15000, 'account' => '3781-101-83706'],
            ['name' => 'Mahabub Hossain Alif', 'amount' => 15000, 'account' => '3781-101-83730'],
            ['name' => 'Shah Amanat Ullah', 'amount' => 15000, 'account' => '3781-101-83747'],
            ['name' => 'MOSHAROF RONY', 'amount' => 9000, 'account' => '3781-101-83594'],
            ['name' => 'Mohammed Abdullah', 'amount' => 11000, 'account' => '3781-101-83693'],
            ['name' => 'Chelsi Rema', 'amount' => 13000, 'account' => '3781-101-83779'],
            ['name' => 'Emelia Ani Areng', 'amount' => 13000, 'account' => '3781-101-83667'],
            ['name' => 'Rakesh Saha', 'amount' => 8000, 'account' => '3781-101-83652'],
            ['name' => 'Barsha Saha', 'amount' => 8000, 'account' => '3781-101-83630'],
            ['name' => 'Riad Mia', 'amount' => 21000, 'account' => '3781-101-83822'],
        ];
 
        foreach ($data as $item) {
            Salary::updateOrCreate(
                [
                    'employee_name' => $item['name'],
                    'month' => $month,
                ],
                [
                    'basic_salary' => $item['amount'],
                    'net_salary' => $item['amount'],
                    'account_number' => $item['account'],
                    'payment_status' => 'pending',
                    'bank_name' => 'Pubali Bank',
                    'bank_branch' => 'Panthapath Branch, Dhaka',
                ]
            );
        }
    }
}
