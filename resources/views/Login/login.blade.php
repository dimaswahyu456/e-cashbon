@extends('layouts.master-without-nav')
@section('title')
@lang('translation.Login')
@endsection
@section('content')
<style>
#loadingModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    z-index: 1050;
    background: rgba(0, 0, 0, 0.5);
}
#loadingModal .modal-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

#loadingModal .modal-content {
    background: transparent;
    border: none;
    box-shadow: none;
}

#loadingModal .modal-body {
    text-align: center;
    color: white;
}

#loadingModal .spinner-border {
    width: 3rem;
    height: 3rem;
}

#loadingModal .mb-4 {
    font-size: 18px;
}
</style>

<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <a href="{{ route('login') }}" class="mb-5 d-block auth-logo">
                        <span class="logo logo-dark">
                            <center><img src="{{ URL::asset('/assets/images/e-cashbon_dark.png') }}" alt="" height="100"></center>
                        </span>
                        <span class="logo logo-light">
                            <center><img src="{{ URL::asset('/assets/images/e-cashbon_dark.png') }}" alt="" height="85"></center>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">                            
                            <h5 class="text-primary">Welcome to E-Cashbon SIG</h5>
                            <p class="text-muted"></p>                                    
                            @if ($errors->has('login'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('login') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-2 mt-4">
                            <form method="POST" action="auth">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Username" value="{{ old('name') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" />
                                </div>

                                <div class="mt-3 text-end">
                                    <button class="btn btn-primary w-sm waves-effect waves-light" type="submit" onclick="showLoadingModal()">Log
                                        In</button>
                                </div>

                            </form>

                            <div class="modal" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body text-center">
                                            <p class="mb-4">Loading...</p>
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <p>© <script>
                            document.write(new Date().getFullYear())
                        </script> Saraswanti Group</p>
                </div>

            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function showLoadingModal() {
        $('#loadingModal').modal('show');
        $('#loginForm button[type=submit]').prop('disabled', true);
    }

    function hideLoadingModal() {
        $('#loadingModal').modal('hide');
        $('#loginForm button[type=submit]').prop('disabled', false);
    }
</script>
