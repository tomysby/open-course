<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function collection()
    {
        return \App\Models\User::with('role')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        $headers = [];
        
        foreach ($this->columns as $column) {
            $headers[] = $this->getColumnHeader($column);
        }
        
        return $headers;
    }

    public function map($user): array
    {
        $row = [];
        
        foreach ($this->columns as $column) {
            $row[] = $this->getColumnValue($user, $column);
        }
        
        return $row;
    }

    protected function getColumnHeader($column)
    {
        switch ($column) {
            case 'name': return 'Nama';
            case 'email': return 'Email';
            case 'role': return 'Role';
            case 'created_at': return 'Tanggal Bergabung';
            default: return ucfirst($column);
        }
    }

    protected function getColumnValue($user, $column)
    {
        switch ($column) {
            case 'name': return $user->name;
            case 'email': return $user->email;
            case 'role': return $user->role->name;
            case 'created_at': return $user->created_at->format('d/m/Y H:i');
            default: return $user->{$column};
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set header row background color
            1 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D9D9D9']]],
        ];
    }
}