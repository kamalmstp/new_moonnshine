<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PangkatGolongan;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<PangkatGolongan>
 */
class PangkatGolonganResource extends ModelResource
{
    protected string $model = PangkatGolongan::class;

    protected string $title = 'Pangkat & Golongan';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Pangkat', 'nama_pangkat'),
            Text::make('Golongan', 'golongan'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Pangkat', 'nama_pangkat')->required(),
                Text::make('Golongan', 'golongan')->required(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            //
        ];
    }

    /**
     * @param PangkatGolongan $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'nama_pangkat' => ['required', 'string', 'max:100'],
            'golongan' => ['required', 'string', 'max:10'],
        ];
    }
}
