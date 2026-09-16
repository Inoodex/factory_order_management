<?php
 
namespace App\Exports;
 
use App\Models\Salary;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
 
class SalaryExport implements FromView, ShouldAutoSize
{
    protected $month;
 
    public function __construct($month)
    {
        $this->month = $month;
    }
 
    public function view(): View
    {
        $salaries = Salary::with('user')
            ->where('month', $this->month)
            ->get();
            
        $totalAmount = $salaries->sum('net_salary');
        $amountInWords = $this->numberToWords($totalAmount);
 
        return view('admin.exports.salary_advice', [
            'salaries' => $salaries,
            'month' => $this->month,
            'amountInWords' => $amountInWords
        ]);
    }
 
    /**
     * Convert number to words (South Asian / Bangladeshi Format)
     */
    private function numberToWords($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
        $digits_2 = array('', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
        $g = array('', 'thousand', 'lac', 'crore');
        
        $res = array();
        $num = number_format($no, 2, ".", "");
        $num_arr = explode(".", $num);
        $whl = $num_arr[0];
        $dec = $num_arr[1];
        
        $whole_arr = array_reverse(explode(",", number_format($whl)));
        krsort($whole_arr);
        $rettxt = "";
        
        if ($whl > 0) {
            if ($whl < 1000000000) {
                $rettxt = $this->convertGroup($whl);
            } else {
                $rettxt = "Amount too large";
            }
        }
        
        return ucfirst($rettxt);
    }

    private function convertGroup($number)
    {
        $no = (int)$number;
        $crore = floor($no / 10000000);
        $no = $no % 10000000;
        $lac = floor($no / 100000);
        $no = $no % 100000;
        $thousand = floor($no / 1000);
        $no = $no % 1000;
        $hundred = floor($no / 100);
        $no = $no % 100;
        $ten = $no;

        $res = "";

        if ($crore > 0) { $res .= $this->convertThreeDigit($crore) . " crore "; }
        if ($lac > 0) { $res .= $this->convertThreeDigit($lac) . " lac "; }
        if ($thousand > 0) { $res .= $this->convertThreeDigit($thousand) . " thousand "; }
        if ($hundred > 0) { $res .= $this->convertThreeDigit($hundred) . " hundred "; }
        if ($ten > 0) { 
            if ($res != "") $res .= "and ";
            $res .= $this->convertThreeDigit($ten);
        }

        return $res;
    }

    private function convertThreeDigit($number)
    {
        $ones = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
        $tens = array('', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
        
        $res = "";
        if ($number >= 100) {
            $res .= $ones[floor($number / 100)] . " hundred ";
            $number %= 100;
        }
        if ($number >= 20) {
            $res .= $tens[floor($number / 10)] . " ";
            $number %= 10;
        }
        if ($number > 0) {
            $res .= $ones[$number];
        }
        return trim($res);
    }
}
