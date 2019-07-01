<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

/**
 * Class BaseController
 * @package Modules\Admin\Http\Controllers
 */
class BaseController extends Controller
{
    /**
     * List of items per page for list.
     *
     * @var array
     */
    protected $listLimits = [10, 25, 50, 100];

    /**
     * Items per page value.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Default sort field.
     *
     * @var string
     */
    protected $sortBy = 'id';

    /**
     * Default sort direction.
     *
     * @var string
     */
    protected $sortDir = 'asc';

    /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id'];

    /**
     * Current locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->cacheTranslations('global_translations_' . $this->locale, resource_path('lang/' . $this->locale));
        $adminPath = __DIR__ .'/../../Resources/lang/' . $this->locale;
        $this->cacheTranslations('admin_translations_' . $this->locale, $adminPath, 'admin');

        if (isset($_GET['items_per_page']) && in_array($_GET['items_per_page'], $this->listLimits)) {
            $this->itemsPerPage = $_GET['items_per_page'];
        }
        if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], $this->sortableFields)) {
            $this->sortBy = $_GET['sort_by'];
        }
        if (isset($_GET['sort_dir']) && in_array($_GET['sort_dir'], ['asc', 'desc'])) {
            $this->sortDir = $_GET['sort_dir'];
        }

        View::share('listLimits', $this->listLimits);
        View::share('itemsPerPage', $this->itemsPerPage);
        View::share('sortBy', $this->sortBy);
        View::share('sortDir', $this->sortDir);
        View::share('currentLocale', $this->locale);
    }

    /**
     * Calculate data for pagination.
     *
     * @param LengthAwarePaginator $data
     * @return array
     */
    protected static function calculatePagination(LengthAwarePaginator $data): array
    {
        $pagination['start'] = (($data->currentPage() - 1) * $data->perPage()) + 1;
        $pagination['end'] = (($data->currentPage() - 1) * $data->perPage()) + $data->perPage();
        if ($pagination['end'] > $data->total()) {
            $pagination['end'] = $data->total();
        }
        $pagination['total'] = $data->total();

        return $pagination;
    }

    /**
     * Handle Exception.
     *
     * @param Exception $exception
     * @param Request $request
     */
    protected function handleException(Exception $exception, Request $request = null)
    {
        Log::error("{$exception->getFile()}({$exception->getLine()})");
        Log::error($exception->getTraceAsString());

        if (config('app.debug')) {
          throw $exception;
        }

        if ($request) {
            $request->session()->flash('error', trans('admin::flash.internal_error'));
        }
    }

    /**
     * Cache translations.
     *
     * @param $variableName
     * @param $path
     * @param null $prefix
     */
    private function cacheTranslations($variableName, $path, $prefix = null)
    {
        Cache::rememberForever($variableName, function () use ($path, $prefix) {
            return collect(File::allFiles($path))->flatMap(function ($file) use ($prefix) {
                return [
                    ($translation = $file->getBasename('.php')) =>
                        trans($prefix ? "$prefix::$translation" : $translation),
                ];
            })->toJson();
        });
    }
}
