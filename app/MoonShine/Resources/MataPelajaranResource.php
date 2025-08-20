<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MataPelajaran;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<MataPelajaran>
 */
class MataPelajaranResource extends ModelResource
{
    protected string $model = MataPelajaran::class;

    protected string $title = 'Mata Pelajaran';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Mata Pelajaran', 'nama_mapel')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Kode Mata Pelajaran', 'kode_mapel'),
                Text::make('Mata Pelajaran', 'nama_mapel'),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('Mata Pelajaran', 'nama_mapel')
        ];
    }

    /**
     * @param MataPelajaran $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'nama_mapel' => ['required', 'string', 'max:100'],
        ];
    }
}
