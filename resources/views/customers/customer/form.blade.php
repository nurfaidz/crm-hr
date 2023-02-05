<div class="form-group {{ $errors->has('nama') ? 'has-error' : ''}}">
    <label for="nama" class="control-label">{{ 'Nama' }}</label>
    <input class="form-control" name="nama" type="text" id="nama" value="{{ isset($customer->nama) ? $customer->nama : ''}}" >
    {!! $errors->first('nama', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('alamat') ? 'has-error' : ''}}">
    <label for="alamat" class="control-label">{{ 'Alamat' }}</label>
    <input class="form-control" name="alamat" type="text" id="alamat" value="{{ isset($customer->alamat) ? $customer->alamat : ''}}" >
    {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('nohp') ? 'has-error' : ''}}">
    <label for="nohp" class="control-label">{{ 'Nohp' }}</label>
    <input class="form-control" name="nohp" type="text" id="nohp" value="{{ isset($customer->nohp) ? $customer->nohp : ''}}" >
    {!! $errors->first('nohp', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label">{{ 'Email' }}</label>
    <input class="form-control" name="email" type="text" id="email" value="{{ isset($customer->email) ? $customer->email : ''}}" >
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
    <label for="facebook" class="control-label">{{ 'Facebook' }}</label>
    <input class="form-control" name="facebook" type="text" id="facebook" value="{{ isset($customer->facebook) ? $customer->facebook : ''}}" >
    {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('instagram') ? 'has-error' : ''}}">
    <label for="instagram" class="control-label">{{ 'Instagram' }}</label>
    <input class="form-control" name="instagram" type="text" id="instagram" value="{{ isset($customer->instagram) ? $customer->instagram : ''}}" >
    {!! $errors->first('instagram', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('whatsapp') ? 'has-error' : ''}}">
    <label for="whatsapp" class="control-label">{{ 'Whatsapp' }}</label>
    <input class="form-control" name="whatsapp" type="text" id="whatsapp" value="{{ isset($customer->whatsapp) ? $customer->whatsapp : ''}}" >
    {!! $errors->first('whatsapp', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('company') ? 'has-error' : ''}}">
    <label for="company" class="control-label">{{ 'Company' }}</label>
    <input class="form-control" name="company" type="text" id="company" value="{{ isset($customer->company) ? $customer->company : ''}}" >
    {!! $errors->first('company', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
