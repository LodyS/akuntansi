<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UpdateSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pull()
    {
        if( Auth::user()->id == 1) {
            echo "<pre>";
            try {
                $pull = Process::fromShellCommandline('sh ./quick_update.sh');
                $pull->setWorkingDirectory(base_path());
                $pull->mustRun();
                $pull->wait();
                echo "\n-------------------------------------------------------\n";
                echo $pull->getOutput();
            } catch (ProcessFailedException $e) {
                echo $e->getMessage();
                exit();
            }

        } else {
            return abort(404);
        }
    }
}
