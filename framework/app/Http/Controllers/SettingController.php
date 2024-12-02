<?php

namespace App\Http\Controllers;

use App\Setting;
use Exception;
use App\Equipment;
use App\User;
use App\CallEntry;
use App\Role;
use Illuminate\Http\Request;
use Auth;
use Validator;

class SettingController extends Controller {

	private $envPath, $envExamplePath;
	
	public function __construct() {
		$this->envPath = base_path('.env');
		$this->envExamplePath = base_path('.env.example');
		
	}
	// public function date_settings(Request $request){
	// 	$date_settings=explode(',',$request->date_settings);
	// 	$env = $this->getEnvContent();
	// 	$rows = explode("\n", $env);
	// 	$unwanted = "date_settings|date_convert";
	// 	$cleanArray = preg_grep("/$unwanted/i", $rows, PREG_GREP_INVERT);
	// 	$cleanString = implode("\n", $cleanArray);
		
	// 	$date_settings="\ndate_settings=$date_settings[0]
    //     date_convert=$date_settings[1]";
	// 	$env = $cleanString . $date_settings;
		
	// 	try {
	// 		file_put_contents($this->envPath, $env);
			
	// 	} catch (Exception $e) {
			
	// 		$message = trans('messages.environment.errors');
	// 	}
		
	// 	return redirect()->back()->with('flash_message', 'Date Settings updated');
	// }
	public function index() {
		$this->check_role();
		$page = 'settings';
		$setting = Setting::first();
		return view('settings.index', compact('page', 'setting'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function post(Request $request) {
		$validator = Validator::make($request->all(), [
			'logo'=>'image|mimes:jpeg,png,jpg,gif',
			'company' => 'required',
		]
		);
		if ($validator->fails()) {
			return redirect()->back()
				->withInput($request->all())
				->withErrors($validator);
		}
		$this->check_role();

		// dd($request->all());
		$settings = Setting::first();
		$env = $this->getEnvContent();
		if ($settings == null) {
			$settings = new Setting;
		}

		if ($file = $request->file('logo')) {
			if ($settings->logo && file_exists('uploads/' . $settings->logo)) {
				unlink('uploads/' . $settings->logo);
			}
			$name = time() . '_logo_' . $file->getClientOriginalName();
			$file->move('uploads', $name);
			$settings->logo = $name;
		}

		if ($request->company) {
			// @ignoreCodingStandard			
			$settings->company = $request->company;
		}
		$this->updateOrAppendEnvValue('locale', $request->language);
		
		$settings->save();

		return redirect()->back()->with('flash_message', 'Settings updated');
	}
	public function updateOrAppendEnvValue($key, $value)
    {
        // Load the contents of the .env file
        $envFile = base_path('.env');
        $currentEnv = file_get_contents($envFile);
		$currentEnv = str_replace("\r\n", "\n", $currentEnv);
        // Check if the key exists in the .env file
        if (strpos($currentEnv, "{$key}=") !== false) {
            // Update the value of the existing key
            $pattern = "/{$key}=.*/";
            $replacement = "{$key}={$value}";
            $updatedEnv = preg_replace($pattern, $replacement, $currentEnv);
            file_put_contents($envFile, $updatedEnv);
        } else {
			$newLine = "{$key}={$value}";
			// Check if the current environment string ends with a newline character
			if (substr($currentEnv, -1) !== "\n") {
				// If it doesn't, prepend a newline character to the new line
				$newLine = "\n" . $newLine;
			}
			file_put_contents($envFile, $newLine, FILE_APPEND);
        }
    }

	public function mailSettings(Request $request) {
		$this->check_role();

		$env = $this->getEnvContent();
		$validator = \Validator::make($request->all(), [
			'mail_driver' => 'required',
			'mail_host' => 'required|regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/',
			'mail_port' => 'required|numeric',
			'mail_username' => 'required',
			'mail_password' => 'required',
			'mail_encryption' => 'required',
		], [
			'mail_host.regex' => __('The mail host must be a valid hostname without invalid characters.'),
		]);
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator, 'mail_errors')
				->withInput();
		}

		$mailDriver = $request->post('mail_driver');
		$mailHost = $request->post('mail_host');
		$mailPort = $request->post('mail_port');
		$mailUsername = $request->post('mail_username');
		$mailPassword = $request->post('mail_password');
		$mailEncryption = $request->post('mail_encryption');

		$smtpSetting = "\nMAIL_DRIVER=$mailDriver
		MAIL_HOST=$mailHost
		MAIL_PORT=$mailPort
		MAIL_USERNAME=$mailUsername
		MAIL_PASSWORD=$mailPassword
		MAIL_ENCRYPTION=$mailEncryption";

		$rows = explode("\n", $env);
		$unwanted = "MAIL_DRIVER|MAIL_HOST|MAIL_PORT|MAIL_USERNAME|MAIL_PASSWORD|MAIL_ENCRYPTION";
		$cleanArray = preg_grep("/$unwanted/i", $rows, PREG_GREP_INVERT);

		$cleanString = implode("\n", $cleanArray);

		$env = $cleanString . $smtpSetting;
		try {
			file_put_contents($this->envPath, $env);
			
		} catch (Exception $e) {
			
			$message = trans('messages.environment.errors');
		}
		
		return redirect()->back()->with('flash_message', 'SMTP Settings updated');

	}

	public function deleteLogo($img) {
		if (file_exists('uploads/' . $img)) {
			$settings = Setting::first();
			$settings->update(['logo' => null]);
			unlink('uploads/' . $img);
			return redirect()->back()->with('flash_message', 'Logo is deleted');

		} else {
			return redirect()->back()->with('flash_message_error', 'Error in Logo delete');
		}
	}

	public function getEnvContent() {
		if (!file_exists($this->envPath)) {
			if (file_exists($this->envExamplePath)) {
				copy($this->envExamplePath, $this->envPath);
			} else {
				touch($this->envPath);
			}
		}

		return file_get_contents($this->envPath);
	}

	public function check_role() {
		if (!auth()->user()->hasRole('Admin')) {
			abort('401');
		}
		return true;
	}

	// public function favicon_store(Request $request)
	// {
	// 	// dd($request->all());
	// 	$settings = Setting::first();
	// 	if ($settings == null) {
	// 		$settings = new Setting;
	// 	}
	// 	if ($file = $request->file('favicon')) {
	// 		if ($settings->favicon && file_exists('uploads/' . $settings->favicon)) {
	// 			unlink('uploads/' . $settings->favicon);
	// 		}
	// 		$name = time() . 'favicon' . $file->getClientOriginalName();
	// 		$file->move('uploads', $name);
	// 		$settings->favicon = $name;
    //         $settings->save();

    //         return redirect()->back()->with('success', 'Favicon uploaded successfully.');
        
	// 	}

    //     return redirect()->back()->with('error', 'Please upload a valid file.');
	// }

	public function favicon_store(Request $request)
{
    $settings = Setting::first();
    if ($settings == null) {
        $settings = new Setting;
    }
    
    // Handle favicon upload
    if ($file = $request->file('favicon')) {
        if ($settings->favicon && file_exists('uploads/' . $settings->favicon)) {
            unlink('uploads/' . $settings->favicon);
        }
        $name = time() . 'favicon' . $file->getClientOriginalName();
        $file->move('uploads', $name);
        $settings->favicon = $name;
    }
    
    $date_settings = explode(',', $request->date_settings);
    $env = $this->getEnvContent();
    $rows = explode("\n", $env);
    $unwanted = "date_settings|date_convert";
    $cleanArray = preg_grep("/$unwanted/i", $rows, PREG_GREP_INVERT);
    $cleanString = implode("\n", $cleanArray);
    
    $date_settings = "\ndate_settings={$date_settings[0]}
date_convert={$date_settings[1]}";
    
    $allow_guest = $request->has('allow_guest') ? 1 : 0;
    // $allow_guest_setting = "\nallow_guest={$allow_guest}";
    
    // $env = $cleanString . $date_settings . $allow_guest_setting;
    $env = $cleanString . $date_settings;


    try {
        file_put_contents($this->envPath, $env);
        $settings->allow_guest = $allow_guest; 
        $settings->save();
    } catch (Exception $e) {
        $message = trans('messages.environment.errors');
    }
    
    return redirect()->back()->with('success', 'Settings updated successfully!');
}

		// public function store(Request $request)
		// {
		// 	$validatedData = $request->validate([
		// 		'name' => 'required|string|max:255',
		// 		'email' => 'required|email|unique:users,email|max:255',
		// 		'nature_of_problem' => 'required|string',
		// 		'equipment_id' => 'required|exists:equipments,id',
		// 	]);
		// 	$user = User::create([
		// 		'name' => $validatedData['name'],
		// 		'email' => $validatedData['email'],
		// 		// 'password' => bcrypt(Str::random(10)), 
		// 	]);
		
		// 	$user->assignRole('Guest');
		
		// 	$equipment = Equipment::find($request->equipment_id);
		// 	$breakdown = new CallEntry;
		// 	$breakdown->user_id = $user->id;
		// 	$breakdown->call_type = 'breakdown';
		// 	$breakdown->equip_id = $request->equipment_id;
		// 	$breakdown->nature_of_problem = $request->nature_of_problem;
		// 	$breakdown->save();
		// }

		public function store(Request $request)
{
    $validatedData = $request->validate([
        'nature_of_problem' => 'required|string',
        'equipment_id' => 'required|exists:equipments,id',
        'user_id' => 'nullable|exists:users,id', 
        'name' => 'required_without:user_id|string|max:255',
        'email' => 'required_without:user_id|email|max:255',
    ]);

    if (Auth::check()) {
        $userId = Auth::id();
    } else {
        $existingUser = User::where('email', $validatedData['email'])->first();
        if ($existingUser) {
            $existingUser->name = $validatedData['name'];
            $existingUser->save();
            $userId = $existingUser->id; 
        } else {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role_id' => Role::where('name', 'Guest')->first()->id,
            ]);
            $user->assignRole('Guest'); 
            $userId = $user->id;
        }
    }

    $breakdown = new CallEntry;
    $breakdown->user_id = $userId; 
    $breakdown->call_type = 'breakdown';
    $breakdown->equip_id = $validatedData['equipment_id'];
    $breakdown->nature_of_problem = $validatedData['nature_of_problem'];
    
    $breakdown->call_handle = $request->call_handle; 

	    $report_no = CallEntry::where('call_handle', 'internal')->count();

    if ($breakdown->call_handle == 'external') {
        $breakdown->report_no = $request->report_no; 
    } elseif ($breakdown->call_handle == 'internal') {
        $breakdown->report_no = $report_no + 1;
    }

    $breakdown->call_register_date_time = $request->has('call_register_date_time') 
        ? \Carbon\Carbon::parse($request->call_register_date_time) 
        : null;

    $breakdown->working_status = $request->working_status ?? null;
    $breakdown->save();

    return redirect()->back()->with('success', 'Breakdown Maintenance Entry created');
}

		public function deleteFavLogo($img) {
			if (file_exists('uploads/' . $img)) {
				$settings = Setting::first();
				$settings->update(['favicon' => null]);
				unlink('uploads/' . $img);
				return redirect()->back()->with('flash_message', 'Logo is deleted');

			} else {
				return redirect()->back()->with('flash_message_error', 'Error in Logo delete');
			}
		}
}
