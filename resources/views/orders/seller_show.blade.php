@extends('layouts.app')
@section('body', '')
@section('content')
    <header class="comhead">
        <h2>订单详情</h2>
        <a href="{{ route('order.seller_index') }}" class="back"></a>
    </header>
    <div class="comheadbg"></div>
    @include('public._message')
    <img src="/images/address.png" class="w100 block" />
    <a class="top_address">
        <span>送达</span>
        <p class="p1">{{ $school->depot }}</p>
    </a>
    <div class="clear h02"></div>

    <div class="w100 whitebg oh">
        <div class="order_menu">
            <i style="background-image: url({{ $buyer->avatar }})"></i>
            <span>{{ $buyer->name }}</span>
            <p>{{ $buyer->school->name }}</p>
        </div>
        <div class="clear"></div>
        <a class="prolist" href="javascript:void(0);">
            <div class="box">
                <i style="background-image: url({{ $order->image }})"></i>
                <p class="p1">{{ $order->name }}</p>
                {{--<p class="p2">作者名字<span>人民教育出版社</span></p>--}}
                <p class="p4"> x 1</p>
                {{--<p class="p5">￥{{ $order->price }}</p>--}}
            </div>
        </a>
    </div>

    <section class="order_btn">
        <div class="box">
            <span class="sp1">订单号：</span>
            <p class="p1">{{ $order->sn }}</p>
        </div>
        <div class="box">
            <span class="sp1">订单状态：</span>
            <p class="p1">{{ $order->status_name }}</p>
        </div>
        {{--<div class="box">
            <span class="sp1">配送时间</span>
            <p class="p1">2018-05-22</p>
        </div>--}}
        {{--<div class="box">
            <span class="sp1">配送点</span>
            <p class="p1 green">已送达</p>
        </div>--}}
        <div class="box">
            <span class="sp1">买家留言：</span>
        </div>
        <div class="box2">
            <p>{{ $order->message }}</p>
        </div>
    </section>
    <div class="clear h02"></div>

    <section class="order_btn">
        <div class="box">
            <span class="sp1">订单日志：</span>
        </div>
        <div class="box2">
            @if($logs)
                @foreach ($logs as $log)
                    <p>{{ $log->name }}<span>{{ $log->created_at->diffForHumans() }}-{{ $log->operator_name }}</span></p>
                @endforeach
            @endif
        </div>
    </section>

    <div class="clear h10"></div>
    <footer class="orderfoot">
        <p class="p1">订单金额：<span>￥{{ $order->price }}</span></p>
        @can('confirm', $order)
            <a href="{{ route('order.confirm', $order) }}" class="sub01">确认订单</a>
        @endcan
        @can('send', $order)
            <a onclick="lc('{{ route('order.send', $order) }}', '请确认您已将书本送达？')" class="sub01">确认送达</a>
        @endcan
    </footer>

@endsection