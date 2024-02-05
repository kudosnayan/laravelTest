<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        // Return a collection of user data
        return collect([$this->user]);
    }

    public function headings(): array
    {
        // Return the column headings
        return ['Name', 'Email'];
    }

    public function map($user): array
    {
        // Map the user data to an array
        return [
            $user->name,
            $user->email,
        ];
    }
}
