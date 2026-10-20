@props(['action', 'label' => 'Delete', 'confirm' => 'Are you sure? This action can be reviewed in Trash/Archive depending on the resource.'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ addslashes($confirm) }}')">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn-destructive']) }}>{{ $label }}</button>
</form>
