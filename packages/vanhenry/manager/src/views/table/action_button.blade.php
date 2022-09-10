@php
$params = [];
if (isset($action['query'])) {
    foreach ($action['query'] as $key => $dataQuery) {
        $params[] = $key . '=' . (isset($dataQuery['fix']) && $dataQuery['fix'] ? $dataQuery['value'] : FCHelper::ep($itemMain, $dataQuery['value']));
    }
}
$showAction = true;
if (isset($action['show'])) {
    foreach ($action['show'] as $key => $actionCheck) {
        if (in_array(FCHelper::ep($itemMain, $actionCheck['key']), $actionCheck['value'])) {
            $showAction = true;
        } else {
            $showAction = false;
            break;
        }
    }
}

if (isset($action['where'])) {
    foreach ($action['where'] as $key => $itemFilter) {
        switch ($itemFilter['operator']) {
            case '>':
                if (FCHelper::ep($itemMain, $itemFilter['field']) > $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '>=':
                if (FCHelper::ep($itemMain, $itemFilter['field']) >= $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '<':
                if (FCHelper::ep($itemMain, $itemFilter['field']) < $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '<=':
                if (FCHelper::ep($itemMain, $itemFilter['field']) <= $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '==':
                if (FCHelper::ep($itemMain, $itemFilter['field']) == $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '!=':
                if (FCHelper::ep($itemMain, $itemFilter['field']) != $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '===':
                if (FCHelper::ep($itemMain, $itemFilter['field']) === $itemFilter['value']) {
                    $showAction = false;
                }
                break;
            case '!==':
                if (FCHelper::ep($itemMain, $itemFilter['field']) !== $itemFilter['value']) {
                    $showAction = false;
                }
                break;
        }
        break;
        if (!$showAction) {
            break;
        }
    }
}
@endphp
@if ($showAction)
    <a href="{{ FCHelper::ep($action, 'url') . (count($params) > 0 ? '?' . implode('&', $params) : '') }}{{ (count($params) > 0 ? '&' : '?') . 'returnurl=' . $urlFull }}" @if (isset($action['target_blank']) && $action['target_blank']) target="_blank" @endif
       class="{{ FCHelper::ep($action, 'class') }} tooltipx {{ $tableData->get('table_map', '') }}" @if (isset($action['content'])) data-content="{{ $action['content'] }}" @endif @if (isset($action['attributes'])) {!! $action['attributes'] !!} @endif>
        <i class="{{ FCHelper::ep($action, 'icon') }}" aria-hidden="true"></i>
        <span class="tooltiptext">{{ FCHelper::ep($action, 'label') }}</span>
    </a>
@endif