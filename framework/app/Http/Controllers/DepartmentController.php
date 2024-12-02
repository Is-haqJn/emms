<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller {

	public function index() {
		$this->availibility('View Departments');
		$data['page'] = 'departments';
		$data['departments'] = Department::all();
		return view('departments.index', $data);
	}
	public static function availibility($method)
    {

    // $r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
			if (\Auth::user()->hasDirectPermission($method)) {
				return true;
			} else {
				abort('401');
			}

        // if (\Auth::user()->hasDirectPermission($method)) {

        //     return true;
        // } elseif (!in_array($method, $r_p)) {
        //     abort('401');
        // } else {
        //     return true;
        // }
    }

	public function create() {
		$this->availibility('Create Departments');
		$data['page'] = 'departments';
		return view('departments.create', $data);
	}

	public function store(DepartmentRequest $request) {
		$department = new Department;
		$department->name = $request->name;
		$department->short_name = $request->short_name;
		$department->save();

		return redirect()->route('departments.index')
			->with('flash_message',
				'Department "' . $department->name . '" Created!');
	}

	public function edit($id) {
		$this->availibility('Edit Departments');
		$data['page'] = 'departments';
		$data['department'] = Department::findOrFail($id);
		return view('departments.edit', $data);
	}

	public function update(DepartmentRequest $request, $id) {
		$department = Department::findOrFail($id);
		$department->name = $request->name;
		$department->short_name = $request->short_name;
		$department->save();

		return redirect()->route('departments.index')
			->with('flash_message',
				'Department "' . $department->name . '" updated!');
	}

	public function destroy($id) {
		$this->availibility('Delete Departments');
		$department = Department::findOrFail($id);
		$department->delete();

		return redirect()->route('departments.index')
			->with('flash_message',
				'Department "' . $department->name . '" deleted!');
	}

	public static function recursive($yourString) {
		$result = "";
		if (strpos($yourString, " ") === false) {
			$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U",
				" ", "-", "_");
			$yourString = str_replace($vowels, "", $yourString);
			$check = Department::where('short_name', $yourString)->first();

			if (is_null($check)) {
				return $yourString;
			}
			$only_one_word = substr($yourString, 0, 1);
			$only_one_word .= substr($yourString, 1, 1);
			$check = Department::where('short_name', $only_one_word)->first();
		} else {
			$words = explode(" ", $yourString);
			$first_char_after_space = substr($words[0], 0, 1);
			$first_char_after_space .= substr($words[1], 0, 1);
			if (array_key_exists(2, $words)) {
				$first_char_after_space .= substr($words[2], 0, 1);
			}
			$check_first_two_char_of_words = Department::where('short_name', $first_char_after_space)->first();
			if ($check_first_two_char_of_words == "") {
				$result = $first_char_after_space;
			}
			if ($result == "") {
				$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", " ", "-", "_");
				$yourString = str_replace($vowels, "", $yourString);
				$count = 1;
				for ($i = 1; $i <= strlen($yourString); $i++) {
					$first_char = substr($yourString, 0, 1);
					$first_char .= substr($yourString, $i, 1);
					$check_first_two_char = Department::where('short_name', $first_char)->first();

					if ($count < strlen($yourString)) {
						if ($check_first_two_char == "") {
							$result = $first_char;
							break;
						}
					}

					$count++;
				}
			}
		}
		return $result;
	}
}
