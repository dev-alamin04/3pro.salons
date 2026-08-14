@extends('backend.layouts.app')
@section('title', ' || Mail Settings')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Mail Settings" subtitle="Configure mail server settings for the application."
            :breadcrumbs="[['text' => 'Mail Settings', 'url' => route('mail.setting')]]" />

        <div class="row">
            <div class="col-lg-12">
                <div class="mb-4 card-style">
                    <div class="card card-body">
                        <form method="POST" action="{{ route('mail.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-4 mt-md-0">
                                    <div class="input-style-1">
                                        <label for="mail_mailer">MAIL MAILER:</label>
                                        <input type="text" placeholder="Enter mail mailer" id="mail_mailer"
                                            class="form-control @error('mail_mailer') is-invalid @enderror"
                                            name="mail_mailer" value="{{ config('mail.default') }}" />
                                        @error('mail_mailer')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4 mt-md-0">
                                    <div class="input-style-1">
                                        <label for="mail_host">MAIL HOST:</label>
                                        <input type="text" placeholder="Enter mail host" id="mail_host"
                                            class="form-control @error('mail_host') is-invalid @enderror" name="mail_host"
                                            value="{{ config('mail.mailers.smtp.host') }}" />
                                        @error('mail_host')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="mail_port">MAIL PORT:</label>
                                        <input type="text" placeholder="Enter mail port" id="mail_port"
                                            class="form-control @error('mail_port') is-invalid @enderror" name="mail_port"
                                            value="{{ config('mail.mailers.smtp.port') }}" />
                                        @error('mail_port')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="mail_username">MAIL USERNAME:</label>
                                        <input type="text" placeholder="Enter mail username" id="mail_username"
                                            class="form-control @error('mail_username') is-invalid @enderror"
                                            name="mail_username" value="{{ config('mail.mailers.smtp.username') }}" />
                                        @error('mail_username')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="mail_password">MAIL PASSWORD:</label>
                                        <input type="text" placeholder="Enter mail password" id="mail_password"
                                            class="form-control @error('mail_password') is-invalid @enderror"
                                            name="mail_password" value="{{ config('mail.mailers.smtp.password') }}" />
                                        @error('mail_password')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="mail_encryption">MAIL ENCRYPTION:</label>
                                        <input type="text" placeholder="Enter mail encryption" id="mail_encryption"
                                            class="form-control @error('mail_encryption') is-invalid @enderror"
                                            name="mail_encryption" value="{{ config('mail.mailers.smtp.encryption') }}" />
                                        @error('mail_encryption')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="mail_from_address">MAIL FROM ADDRESS:</label>
                                        <input type="text" placeholder="Enter mail from address" id="mail_from_address"
                                            class="form-control @error('mail_from_address') is-invalid @enderror"
                                            name="mail_from_address" value="{{ config('mail.from.address') }}" />
                                        @error('mail_from_address')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <div class="input-style-1">
                                        <label for="app_name">APP NAME:</label>
                                        <input type="text" placeholder="Enter app name" id="app_name"
                                            class="form-control @error('app_name') is-invalid @enderror"
                                            name="app_name" value="{{ config('app.name') }}" />
                                        @error('app_name')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-md me-3">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
