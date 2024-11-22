<?php

namespace App\Http\Controllers;

use Imagick;
use Exception;
use ImagickPixel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\PDF;
use AudioManager\Manager;
use App\Helper\SlipDesign;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Models\MilkBuyRecords;
use Mike42\Escpos\EscposImage;
use AudioManager\Adapter\Google;
use Mike42\Escpos\GdEscposImage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkSaleRecords;
use Mike42\Escpos\CapabilityProfile;
use Illuminate\Support\Facades\Route;
use Mike42\Escpos\ImagickEscposImage;
use Chocofamilyme\LaravelVoiceCall\VoicecallService;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TestController extends Controller
{

    public function getCountryCity()
    {
        $serviceAccountPath = storage_path('countries+states+cities.json');
        if (file_exists($serviceAccountPath)) {
            $fileContents = file_get_contents($serviceAccountPath);
            $countries =  collect(json_decode($fileContents));
           return $cn =  $countries->where('id', 101)->first();
            $in  = [
                'name' => $cn->name,
                'iso2' => $cn->iso2,
                'region' => $cn->region,
                'currency' => $cn->currency,
                'currency_name' => $cn->currency_name,
                'currency_symbol' => $cn->currency_symbol,
                'timezones' => json_encode($cn->timezones),
            ];
            $cID = DB::table('countries')->insertGetId($in);
            $states = $cn->states;
            foreach ($states as $st) {
                $city = $st->cities;
                $stID =  DB::table('states')->insertGetId([
                    'country_id' => $cID,
                    'name' => $st->name,
                    'state_code' => $st->state_code,
                    'type' => $st->type,
                ]);
                foreach ($city as $ct) {
                    DB::table('cities')->insert([
                        'name' => $ct->name,
                        'state_id' => $stID,
                    ]);
                }
            }
        } else {
            return "Data not found";
        }
    }
    public function firebase()
    {
        return response()->json(['message' => 'Push notification sent successfully']);
    }


    public function index()
    {

        return "test closed";
        $pdfPath = public_path('storage/milk_slips/slip_82.pdf');
        $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
        $pdf->save('exampal');
        return "hello";
        $printer = $this->getPrinter();
        $printer->feed();
        $printer->feed();
        $printer->cut();
        $printer->close();
        return  $this->getPdf($printer);
    }
    public function getPrinter()
    {
        $profile = CapabilityProfile::load("default");
        $connector = new WindowsPrintConnector("EPSON L3110 Series");
        return  $printer = new Printer($connector, $profile);
    }
    public function getPdf($printer)
    {
        $pdfPath = public_path('storage/milk_slips/slip_82.pdf');
        $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
        $pdf->save('exampal');
        return "hello";
        try {
            $pages = GdEscposImage::load($pdfPath);
            foreach ($pages as $page) {
                $printer->graphics($page);
            }
            $printer->cut();
            $printer->close();
            return 'printed';
        } catch (Exception $e) {
            return "Couldn't print to this printer: " . $e->getMessage() . "\n";
        }
    }

    public function printDocument(Request $request)
    {
        $profile = CapabilityProfile::load("default");
        $connector = new WindowsPrintConnector("EPSON L3110 Series");
        $printer = new Printer($connector, $profile);
        $printer->close();
        // Specify the name of the Blade view file
        $viewName = 'pdf.demo.demo'; // Adjust with your actual view name

        // Render the Blade view to HTML content
        $htmlContent = view($viewName)->render();

        // Set width for wkhtmltoimage
        $width = 550;

        // Generate a temporary destination file for the image
        $dest = tempnam(sys_get_temp_dir(), 'escpos') . ".png";

        // Command to convert HTML to image using wkhtmltoimage
        $command = sprintf(
            "wkhtmltoimage -n -q --width %s - %s",
            escapeshellarg($width),
            escapeshellarg($dest)
        );

        // Test for dependencies
        foreach (["wkhtmltoimage"] as $cmd) {
            $testCmd = sprintf("which %s", escapeshellarg($cmd));
            exec($testCmd, $testOut, $testStatus);
            if ($testStatus != 0) {
                throw new Exception("You require $cmd but it could not be found");
            }
        }

        // Run wkhtmltoimage
        $descriptors = [
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ];
        $process = proc_open($command, $descriptors, $fd);
        if (is_resource($process)) {
            // Write HTML content to stdin of wkhtmltoimage process
            fwrite($fd[0], $htmlContent);
            fclose($fd[0]);

            // Read stdout
            $outputStr = stream_get_contents($fd[1]);
            fclose($fd[1]);

            // Read stderr
            $errorStr = stream_get_contents($fd[2]);
            fclose($fd[2]);

            // Finish up
            $retval = proc_close($process);
            if ($retval != 0) {
                throw new Exception("Command $cmd failed: $outputStr $errorStr");
            }
        } else {
            throw new Exception("Command '$cmd' failed to start.");
        }

        // Load up the image using EscposImage
        try {
            $img = EscposImage::load($dest);
        } catch (Exception $e) {
            unlink($dest);
            throw $e;
        }
        unlink($dest);

        // Assuming $printer is initialized earlier in your code
        $printer->bitImage($img);
        $printer->cut();

        return response()->json(['success' => true, 'message' => 'Document printed successfully']);
    }

    public function test()
    {
        $profile = CapabilityProfile::load("default");
        $connector = new WindowsPrintConnector("EPSON L3110 Series");
        $printer = new Printer($connector, $profile);
        $printer->close();

        // $record = (object) [
        //     'seller' => (object) [
        //         'name' => 'John Doe',
        //         'father_name' => 'Richard Doe',
        //         'country_code' => '+91',
        //         'mobile' => '7234567890'
        //     ],
        //     'date' => '2023-06-19',
        //     'shift' => 'Morning',
        //     'milk_type' => 1,
        //     'quantity' => 10,
        //     'fat' => 3.5,
        //     'snf' => 8.5,
        //     'clr' => 30,
        //     'price' => 45,
        //     'total_price' => 450
        // ];

        // $user = (object) [
        //     'name' => 'Admin',
        //     'country_code' => '+91',
        //     'mobile' => '9987654321'
        // ];
        // $printer->setPrintWidth(192);
        // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_EMPHASIZED);
        // $printer->setJustification(Printer::JUSTIFY_CENTER);
        // $printer->feed();
        // $printer->text("MYDAIRY\n");
        // $printer->selectPrintMode();
        // $printer->text("Milk Slip\n");
        // $printer->feed();
        // // Print seller info
        // $printer->setEmphasis(true);
        // $printer->text("{$record->seller->name} s/o {$record->seller->father_name}\n");
        // $printer->text("Mobile No.: {$record->seller->country_code} {$record->seller->mobile}\n");
        // $printer->setEmphasis(false);
        // $printer->feed();
        // // Print details
        // $details = [
        //     "Date" => $record->date,
        //     "Shift" => $record->shift,
        //     "Milk Type" => array_search($record->milk_type, MILK_TYPE),
        //     "Weight" => number_format($record->quantity, 2) . " Ltr",
        //     "FAT" => $record->fat != 0 ? number_format($record->fat, 2) : 'NA',
        //     "SNF" => $record->snf != 0 ? number_format($record->snf, 2) : 'NA',
        //     "CLR" => $record->clr != 0 ? number_format($record->clr, 2) : 'NA',
        //     "Rate/ltr" => "₹" . number_format($record->price, 2),
        //     "Total" => "₹" . number_format($record->total_price, 2)
        // ];
        // foreach ($details as $key => $value) {
        //     $printer->text(str_pad($key, 20, " ") . ": " . str_pad($value, 20, " ", STR_PAD_LEFT) . "\n");
        // }
        // $printer->feed();
        // // Print footer
        // $printer->setEmphasis(true);
        // $printer->text($user->name . "\n");
        // $printer->text("Mobile No.: {$user->country_code} {$user->mobile}\n");
        // $printer->setEmphasis(false);

        // $printer->cut();
        // $printer->feed(Printer::CUT_FULL, 0);
        // $printer->close();
        return 'printed';
        return view('errors.404');
    }

    // public function olodtest(){
    //      //
    //     $connector = new WindowsPrintConnector("EPSON L3110 Series");
    //     $printer = new Printer($connector);
    //     $printer->text("Hello World!\n");
    //     $printer->cut();
    //     // $html = view('pdf.demo.demo');//->render();
    //     $pdfPath = public_path('storage/milk_slips/slip_82.pdf');

    //     $this->getImageFromPdf($pdfPath);
    //     $img = 'apple-touch-icon.png';
    //     try {
    //         $tux  = EscposImage::load($img);
    //         $printer->graphics($tux);
    //         $printer->text("Regular Tux.\n");
    //         $printer->feed();
    //         $printer->close();
    //         return 'Print done';
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
    // private function getImageFromPdf($pdf)
    // {
    //     $im = new imagick($pdf . '[0]');
    //     $im->setResolution(192, 480); //2=192,5=480,3=288
    //     // $im->readimage($pdf . '[0]');
    //     $im->setImageFormat('jpeg');
    //     $im->writeImage('thumb.jpg');
    //     $im->clear();
    //     $im->destroy();
    // }
    // public function generateImage()
    // {
    //     $imagick = new Imagick();
    //     $imagick->newImage(200, 200, new ImagickPixel('lightblue'));
    //     $imagick->setImageFormat('png');
    //     $filename = 'generated_image.png';
    //     $imagick->writeImage(storage_path('app/public/' . $filename));
    //     return response()->download(storage_path('app/public/' . $filename));
    // }
    private function getPrint()
    {
        $connector = new WindowsPrintConnector("EPSON L3110 Series");
        $printer = new Printer($connector);
        $printer->text("Hello World!\n");
        $printer->cut();
        $printer->close();

        $pdfPath = storage_path('app/milk_slips/slip_82.pdf');
        try {
            $pages = ImagickEscposImage::loadPdf($pdfPath); //isGdLoaded(); //loadPdf($pdfPath);
            foreach ($pages as $page) {
                $printer->graphics($page);
            }
            $printer->cut();
            $printer->close();
        } catch (Exception $e) {
            return "Couldn't print to this printer: " . $e->getMessage() . "\n";
        }
    }

    private function getRoutes()
    {
        $routes = Route::getRoutes();
        $userRoutes = [];
        $ignorePrefixes = ['dashboard', 'childUser', 'profile', 'lang', 'settings', 'masters',];
        $ignoreSuffixes = ['info', 'calculate', 'print', 'print.all'];
        foreach ($routes as $route) {
            $routeName = $route->getName();
            if ($routeName && strpos($routeName, 'user.') === 0) {
                $formattedRouteName = substr($routeName, strlen('user.'));
                $shouldIgnore = false;
                foreach ($ignorePrefixes as $prefix) {
                    if (strpos($formattedRouteName, $prefix) === 0) {
                        $shouldIgnore = true;
                        break;
                    }
                }

                if (!$shouldIgnore) {
                    $shouldIgnore2 = false;
                    foreach ($ignoreSuffixes as $suffix) {
                        if (substr($formattedRouteName, -strlen($suffix)) === $suffix) {
                            $shouldIgnore2 = true;
                            break;
                        }
                    }
                    if (!$shouldIgnore2) {
                        $userRoutes[][$formattedRouteName] = 'required';
                    } else {
                        $userRoutes[][$formattedRouteName] = 'not required';
                    }
                }
            }
        }


        return $userRoutes;
    }
}
