<?php

use Illuminate\Support\Arr;

if (! function_exists('getDayFromStringDate')) {
    function getDayFromStringDate($date){
        $dateParts = explode('-', $date);
        $day = $dateParts[2];
        
        return $day;
    }
}

if (! function_exists('getMonthFromStringDate')) {
    function getMonthFromStringDate($date){
        $dateParts = explode('-', $date);
        $month = $dateParts[1];
        
        return getMonthName($month);
    }
}

if (! function_exists('getMonthName')) {
    function getMonthName($month){
        $monthName = '';

        switch($month) {
            case '01': $monthName = 'Enero'; break;
            case '02': $monthName = 'Febrero'; break;
            case '03': $monthName = 'Marzo'; break;
            case '04': $monthName = 'Abril'; break;
            case '05': $monthName = 'Mayo'; break;
            case '06': $monthName = 'Junio'; break;
            case '07': $monthName = 'Julio'; break;
            case '08': $monthName = 'Agosto'; break;
            case '09': $monthName = 'Septiembre'; break;
            case '10': $monthName = 'Octubre'; break;
            case '11': $monthName = 'Noviembre'; break;
            case '12': $monthName = 'Diciembre'; break;
        }

        return $monthName;
    }
}

if (! function_exists('getProp')) {
    function getProp($item = '', $object_path = ''){
        if ($item) {
            $object_path = str_replace('->', '.', $object_path);
            
            return Arr::get($item, $object_path);
        }

        return $item;
    }
}

if (! function_exists('getSortableIconDirection')) {
    function getSortableIconDirection($orderField, $defaultOrderDirection, $isDefaultOrderField = false){
        $iconDirection = '';
        $requestedOrderBy = app('request')->orderBy;
        $requestedOrderDirection = app('request')->orderDirection;

        if ($requestedOrderBy == $orderField) {
            $iconDirection = $requestedOrderDirection == 'asc' ? 'up' : 'down';
        } else if (!$requestedOrderBy && $isDefaultOrderField) {
            $iconDirection = $defaultOrderDirection == 'asc' ? 'up' : 'down';
        }
        
        return $iconDirection;
    }
}

if (! function_exists('getSortableUrl')) {
    function getSortableUrl($orderField, $defaultOrderDirection, $routeString = '', $routeParams = [], $isDefaultOrderField = false){
        $requestedOrderBy = app('request')->orderBy;
        $requestedOrderDirection = app('request')->orderDirection;

        if ($requestedOrderBy == $orderField) {
            $newOrderDirection = $requestedOrderDirection == 'asc' ? 'desc' : 'asc';
        } else if ($isDefaultOrderField && !$requestedOrderBy) {
            $newOrderDirection = $defaultOrderDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $newOrderDirection = $defaultOrderDirection;
        }
        
        $requestParams = app('request')->all();
        $requestParams['orderBy'] = $orderField;
        $requestParams['orderDirection'] = $newOrderDirection;

        $queryUrl = Arr::query($requestParams);

        if ($routeString) {
            $url = route($routeString, $routeParams);
        } else {
            $url = app('request')->path();
        }
        
        return $url . '?' . $queryUrl;
    }
}

if (! function_exists('getYearFromStringDate')) {
    function getYearFromStringDate($date){
        $dateParts = explode('-', $date);
        $year = $dateParts[0];
        
        return $year;
    }
}

if (! function_exists('isFullYearWithDates')) {
    function isFullYearWithDates($start_date, $end_date){
        $isFullYearWithDates = false;

        if ($start_date && $end_date) {
            $year = getYearFromStringDate($start_date);

            $yearInitDate = $year . '-01-01';
            $yearFinishDate = $year . '-12-31';

            if ($start_date == $yearInitDate && $end_date == $yearFinishDate) {
                $isFullYearWithDates = true;
            }
        }
        
        return $isFullYearWithDates;
    }
}