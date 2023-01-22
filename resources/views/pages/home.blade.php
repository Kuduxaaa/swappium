@extends('layouts.main')

@section('title') Crypto Echange @endsection

@section('content')
    @include('includes.marquee')
    @include('includes.navigation')

    <main>
        <div class="exchange bg-bubble">
            <div class="container">
                <div class="text-center exchange-title">
                    <h1>Crypto Exchange</h1>
                    <p>Free from sign-up, limits, complications</p>
                </div>

                <div class="form-wrapper">
                    <form action="" id="exchange">
                        <div class="flex field">
                            <div class="input-group">
                                <label for="send">You send</label>
                                <input type="text" class="input" name="send" value="0.1">
                            </div>

                            <select name="currency" class="select-menu" is="ms-dropdown">
                                <option value="inch" data-image="assets/img/coins/1inch.svg">1INCH</option>
                                <option value="btn" data-image="assets/img/coins/btc.svg">BTC</option>
                                <option value="usdt" data-image="assets/img/coins/usdt.svg">USDT</option>
                                <option value="shib" data-image="assets/img/coins/shib.svg">SHIB</option>
                                <option value="ltc" data-image="assets/img/coins/ltc.svg">LTC</option>
                            </select>
                        </div>

                        <div class="flex field">
                            <div class="input-group">
                                <label for="get">You get</label>
                                <input type="text" class="input" name="get" value="0.00000219">
                            </div>

                            <select name="currency" class="select-menu" is="ms-dropdown">
                                <option value="inch" data-image="assets/img/coins/1inch.svg">1INCH</option>
                                <option value="btn" data-image="assets/img/coins/btc.svg">BTC</option>
                                <option value="usdt" data-image="assets/img/coins/usdt.svg">USDT</option>
                                <option value="shib" data-image="assets/img/coins/shib.svg">SHIB</option>
                                <option value="ltc" data-image="assets/img/coins/ltc.svg">LTC</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Exchange</button>
                    </form> 
                </div>
            </div>
        </div>
    </main>
    
    @include('includes.footer')

@endsection