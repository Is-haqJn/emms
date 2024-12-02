<?php

namespace App\Exports;

use App\Equipment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings {
	use Exportable;
	/**
	 * @return \Illuminate\Support\Collection
	 */
	private $query;

	public function __construct(string $query) {
		$this->query = $query;
	}
	public function query() {
		return Equipment::where('hospital_id', $this->query)->get();
	}
	public function headings(): array
	{
		return [
			'#',
			'Name',
			'email id',
			'role_id',
			'',
			'created_at',
			'updated_at',
		];
	}
}
