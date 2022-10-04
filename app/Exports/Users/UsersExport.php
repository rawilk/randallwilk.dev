<?php

declare(strict_types=1);

namespace App\Exports\Users;

use App\Models\User\User;
use Carbon\CarbonInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Rawilk\LaravelBase\Concerns\Exports\FormatsColumns;

final class UsersExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    use Exportable;
    use FormatsColumns;

    private array $columns = ['id', 'first_name', 'last_name', 'email', 'timezone', 'client_id', 'created_at', 'updated_at'];

    public function __construct(private $query)
    {
    }

    public function usingColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function map($user): array
    {
        return collect($this->columns)
            ->map(function ($column) use ($user) {
                $value = $this->resolveColumn($user, $column);

                if ($value instanceof CarbonInterface) {
                    return Date::dateTimeToExcel($value);
                }

                return $value;
            })
            ->toArray();
    }

    private function resolveColumn(User $user, string $column)
    {
        return match ($column) {
            'permissions' => $user->getPermissionNames()->toJson(),
            'roles' => $user->getRoleNames()->toJson(),
            default => $user->{$column},
        };
    }
}
