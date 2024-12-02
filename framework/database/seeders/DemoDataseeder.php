<?php
namespace Database\Seeders;
use App\Calibration;
use App\CallEntry;
use App\Department;
use App\Equipment;
use App\Hospital;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Faker\Factory;
use Illuminate\Support\Arr;
use Faker;
use QrCode;
use App\QrGenerate;
class DemoDataseeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create();

		$hospital_suffix = ['Hospital', 'Medical', 'Care', 'Medicare', 'Dental', 'Clinic', 'Trust', 'Org'];

		// 15 duplicate records
		for ($i = 1; $i <= 5; $i++) {

			/**
			 * Dummy Hospital Create code below
			 */
			$hospital = Hospital::create([
				'name' => $faker->company . " " . $hospital_suffix[array_rand($hospital_suffix)],
				'email' => $faker->companyEmail,
				'contact_person' => $faker->email,
				'phone_no' => $faker->phoneNumber,
				'user_id' => 1,
				'mobile_no' => $faker->e164PhoneNumber,	
				'address' => $faker->city,
				'slug'=>$faker->unique()->text(6),
			]);
			$hospital->save();
			$user = User::find(1);
			$user->hospitals()->sync([1,2,3,4,5]);
			// $user->save();

		
			$department = Department::create([
				'name' => $faker->company,
			]);
			$department->short_name = $this->recursive($department->name, 'Department');
			$department->save();

			$equipment = Equipment::create([
				'name' => $faker->company,
				'company' => $faker->company,
				'model' => $faker->swiftBicNumber,
				'hospital_id' => $hospital->id,
				'user_id' => 1,
				'sr_no' => $faker->randomNumber(7),
				'department' => $department->id,
				'date_of_purchase' => date('Y-m-d', strtotime('+1 days')),
				'order_date' => date('Y-m-d', strtotime('-3 days')),
				'date_of_installation' => date('Y-m-d', strtotime('+1 days')),
				'warranty_due_date' => date('Y-m-d', strtotime('+3 years')),
				'service_engineer_no' => $faker->e164PhoneNumber,
				'is_critical' => rand(1, 0),
			]);
			$equipment->short_name = $this->recursive($equipment->name, 'Equipment');
			$equipment_number = Equipment::where('hospital_id', $equipment->hospital_id)
				->where('name', trim($equipment->name))
				->where('short_name', $equipment->short_name)
				->where('department', $equipment->department)
				->count();
			$equipment_number = sprintf("%02d", $equipment_number + 1);
			$equipment->unique_id = "";
			$hospital = Hospital::where('id', $equipment->hospital_id)->first();
			if ($hospital != "")
			// {
			//     $unique_id = $hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/' . $equipment_number;

			//     $equipment->unique_id = $unique_id;
			// }
			{
				$unique_id = $hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/' . $equipment_number;
				$label_name = $hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/';
				$equipment_last = Equipment::where('unique_id', 'like', $label_name . '%')->orderBy('unique_id', 'desc')->first();
				if ($equipment_last) {
					$last_label_no = explode('/', $equipment_last->unique_id);
					$last_label_no = end($last_label_no);
					$equipment_number = sprintf("%02d", ((int)$last_label_no) + 1);
					$unique_id = $label_name . $equipment_number;
				} else {
					$unique_id = $label_name . "01";
				}
				$equipment->unique_id = $unique_id;
			}
			// $equipment_number = Equipment::where('hospital_id', $equipment->hospital_id)
			// 	->where('name', trim($equipment->name))
			// 	->where('short_name', $equipment->short_name)
			// 	->where('department', $equipment->department)
			// 	->count();
			// $equipment_number = sprintf("%02d", $equipment_number + 1);

			// $hospital = Hospital::where('id', $equipment->hospital_id)->first();
			// if ($hospital != "") {
			// 	$unique_id = $hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/' . $equipment_number;

			// 	$equipment->unique_id = $unique_id;
			// }
			$qr = new QrGenerate;
            $qr->assign_to = $equipment->id;
            $qr->uid = Str::random(11);
            $qr->save();
			$equipment->qr_id = $qr->id;
            // $equipment->save();
			$equipment->save();
			$id=$equipment->id;
            $url = url('/') . "/scan/qr/" . $qr->uid;
						$qrFilePath = base_path('../uploads/qrcodes/qr_assign/' . $qr->uid . '.png');
					// 	if (class_exists(Imagick::class)) {
            		\QrCode::format('png')->size(300)->generate($url, $qrFilePath);
					// 	}
		
						
			if ($i % 2 == 0) {
				/**
				 * Dummy Breakdown/Preventive Maintenance code below
				 */
				$call_type = ['breakdown', 'preventive'];
				$call_handle = ['internal', 'external'];
				$working_status = ['working', 'not working', 'pending'];
				$callEntry = new CallEntry;
				$callEntry->call_handle = $call_handle[array_rand($call_handle)];
				$callEntry->call_type = $call_type[array_rand($call_type)];
				$callEntry->equip_id = $equipment->id;
				$callEntry->user_id = $equipment->user_id;
				$report_no = CallEntry::where('call_handle', 'internal')->count();
				if ($callEntry->call_handle == 'external') {
					$callEntry->report_no = $faker->randomNumber(5);
				} elseif ($callEntry->call_handle == 'internal') {
					$callEntry->report_no = $report_no + 1;
				}

				$callEntry->call_register_date_time = Carbon::now();

				if ($callEntry->call_type == 'preventive') {
					$callEntry->next_due_date = date('Y-m-d', strtotime('+1 week'));
				}

				$callEntry->working_status = $working_status[array_rand($working_status)];
				$callEntry->nature_of_problem = "Issue: " . $callEntry->working_status;
				$callEntry->is_contamination = rand(1, 0);
				$callEntry->save();

				/**
				 * Dummy Calibrations create code below
				 */
				$days = rand(2, 7);

				$calibration = new Calibration;
				$calibration->date_of_calibration = date('Y-m-d', strtotime('+1 day'));
				$calibration->due_date = date('Y-m-d', strtotime("+$days days"));
				$calibration->equip_id = $equipment->id;
				$calibration->user_id = $equipment->user_id;
				$calibration->certificate_no = $faker->randomNumber(5);
				$calibration->company = $faker->company;
				$calibration->contact_person = $faker->name;
				$calibration->contact_person_no = $faker->e164PhoneNumber;
				$calibration->engineer_no = $faker->e164PhoneNumber;
				$calibration->traceability_certificate_no = $faker->randomNumber(7);
				$calibration->save();
			}
		}

	}

	public static function recursive($yourString, $table_name = "") {
		$result = "";

		switch ($table_name) {
		case 'Hospital':
			$table = Hospital::select('*');
			break;
		case 'Department':
			$table = Department::select('*');
			break;
		case 'Equipment':
			$table = Equipment::select('*');
			break;
		default:
			$table = "";
			die("no table found.");
			break;
		}

		if (strpos($yourString, " ") === false) {
			$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U",
				" ", "-", "_", "'", "\"");
			$yourString_firstchar = substr($yourString, 0, 1);
			$yourString_other = str_replace($vowels, "", substr($yourString, 1));
			$yourString = $yourString_firstchar . $yourString_other;
			$only_one_word = substr($yourString, 0, 1);
			$only_one_word .= substr($yourString, 1, 1);
			if ($table_name == 'Hospital') {
				$check = $table->where('slug', $only_one_word)->first();
			} else {
				$only_one_word .= substr($yourString, 2, 1);
				$check = $table->where('short_name', $only_one_word)->first();
			}
			if (is_null($check)) {
				$result = $only_one_word;
			}
		} else {
			$words = explode(" ", $yourString);
			$first_char_after_space = substr($words[0], 0, 1);
			$first_char_after_space .= substr($words[1], 0, 1);
			if (array_key_exists(2, $words) && $table_name != 'Hospital') {
				$first_char_after_space .= substr($words[2], 0, 1);
			}
			if (array_key_exists(3, $words) && $table_name == 'Department') {
				$first_char_after_space .= substr($words[3], 0, 1);
			}
			if ($table_name == 'Hospital') {

				$check_first_two_char_of_words = $table->where('slug', $first_char_after_space)->first();
			} else {
				$check_first_two_char_of_words = $table->where('short_name', $first_char_after_space)->first();
			}
			if (is_null($check_first_two_char_of_words)) {
				$result = $first_char_after_space;
			}
			if (isset($result) && $result == "") {
				$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U",
					" ", "-", "_");
				$yourString_first_char = substr($yourString, 0, 1);
				$yourString_other = str_replace($vowels, "", substr($yourString, 1));
				$yourString = $yourString_first_char . $yourString_other;
				$count = 1;
				for ($i = 1; $i <= strlen($yourString); $i++) {
					$first_char = substr($yourString, 0, 1);
					$first_char .= substr($yourString, $i, 1);

					if ($table_name == 'Hospital') {
						$check_first_two_char = $table->where('slug', $first_char)->first();
						if (is_null($check_first_two_char)) {
							$result = $first_char;
							return $result;
						} else {
							$first_char = substr($yourString, 0, 1);
							$othr = substr($yourString, 1);
							$yourString = $first_char . $othr;
							self::recursive($yourString, 'Hospital');
						}
					} else {
						$check_first_two_char = $table->where('short_name', $first_char)->first();
						if (is_null($check_first_two_char)) {
							$result = $first_char;
							return $result;
						} else {
							$first_char = substr($yourString, 0, 1);
							$othr = substr($yourString, $i);
							$yourString = $first_char . $othr;
							return self::recursive($yourString, $table_name);
						}
					}

					$count++;
				}
			}
		}
		return $result;
	}
}
