<div class="form-group {{ $errors->has('start') ? 'has-error' : ''}}">
    <label for="start" class="control-label">{{ 'Start' }}</label>
    <input class="form-control flatpickr-basic" name="start" type="text" id="tanggal" value="{{ isset($event->start) ? $event->start : ''}}" >
    {!! $errors->first('start', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('nama_event') ? 'has-error' : ''}}">
    <label for="nama_event" class="control-label">{{ 'Nama Event' }}</label>
    <input class="form-control" name="nama_event" type="text" id="nama_event" value="{{ isset($event->nama_event) ? $event->nama_event : ''}}" >
    {!! $errors->first('nama_event', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('tempat_event') ? 'has-error' : ''}}">
    <label for="tempat_event" class="control-label">{{ 'Tempat Event' }}</label>
    <input class="form-control" name="tempat_event" type="text" id="tempat_event" value="{{ isset($event->tempat_event) ? $event->tempat_event : ''}}" >
    {!! $errors->first('tempat_event', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('finish') ? 'has-error' : ''}}">
    <label for="finish" class="control-label">{{ 'Finish' }}</label>
    <input class="form-control flatpickr-basic mb-2" name="finish" type="text" id="finish" value="{{ isset($event->finish) ? $event->finish : ''}}" >
    {!! $errors->first('finish', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
