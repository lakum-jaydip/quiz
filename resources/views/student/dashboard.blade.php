@extends('layouts.layoutMaster')
@section('title',env('WEB_NAME').' | Student Dashboard')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Student Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible mx-3">
                <div class="d-flex gap-2">
                    <h5><i class="icon fas fa-check"></i></h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert  alert-danger alert-dismissible mx-3">
                <div class="d-flex gap-2">
                    <h5><i class="icon fas fa-ban"></i></h5>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            </div>
        @endif

        <section class="content m-2 ">
            <div class="card card-primary {{empty($subscription)?'':' d-none'}}">
                <div class="row m-3">
                    <div class="col-md-{{empty($subscription)?'12':'12 d-none'}}">
                        <h4>Subscription Plan</h4>
                        <div class="card-deck mb-3 text-center">
                            <div class="card mb-4 box-shadow">
                                <div class="card-header">
                                    <h4 class="my-0 font-weight-normal">Free PLan</h4>
                                </div>
                                <div class="card-body pricing">
                                    <h1 class="card-title pricing-card-title w-100 text-bold">$0</h1>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check text-success"></i> One {{$setting->no_of_questions}}
                                            minute exam once a week
                                        </li>
                                        <li><i class="fa fa-times text-danger"></i> Unable to see previous correct
                                            answers
                                        </li>
                                        <li><i class="fa fa-times text-danger"></i> Unable to look at full working out
                                            for questions
                                        </li>
                                        <li><i class="fa fa-times text-danger"></i> Solution not displayed during the
                                            test
                                        </li>
                                    </ul>
                                    <button disabled type="button" class="btn btn-lg btn-block btn-primary">
                                        Purchased
                                    </button>
                                </div>
                            </div>
                            <div class="card mb-4 box-shadow">
                                <div class="card-header">
                                    <h4 class="my-0 font-weight-normal">Paid Plan</h4>

                                </div>
                                <div class="card-body pricing">
                                    <h1 class="card-title pricing-card-title w-100 text-bold">
                                        ${{ ucfirst($setting->subscription_charge) }}</h1>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check text-success"></i> Unlimited Exam Generations that are
                                            all {{$setting->no_of_questions}} minute long
                                        </li>
                                        <li><i class="fa fa-check text-success"></i> Able to see previous correct
                                            answers
                                        </li>
                                        <li><i class="fa fa-check text-success"></i> Able to look at full working out
                                            for questions
                                        </li>
                                        <li><i class="fa fa-check text-success"></i> Recurring subscription via Stripe
                                        </li>
                                    </ul>
                                    <a target="_blank" href=" {{url('payment/create-checkout-session/')}}"
                                       {{ !empty($subscription) ? 'disabled':'' }}
                                       class="btn btn-lg btn-block btn-primary">
                                        {{ !empty($subscription) ? 'Plan Purchased':'Buy' }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-primary mt-3 mb-5">
                <div class="row m-3">

                    <div class="col-md-6 m-auto">
                        <h4 class="mb-1">Questions done / Topic</h4>
                        @if(!empty($subscription))
                            <p class="mb-3"><strong>Your Plan {{$subscription->status == 'active' ? 'Renewal':'End'}}  On
                                    : </strong> {{ !empty($subscription) ? date('d-m-Y',strtotime($subscription->end_date)):'' }}
                            </p>
                            @if(!$subscriptionStatus)
                                <p class="mb-3 text-danger"><strong>Your Subscription Disable by Owner</strong></p>
                            @endif
                        @endif
                        <div class="card p-3 box-shadow">
                            @foreach($topicData as $topicItem)
                                <div class="progress-group">
                                    {{$topicItem['title']}}
                                    <span class="float-right">
                                    <b>{{$topicItem['attempted_questions']}}</b>/
                                        {{$topicItem['total_questions']}}
                                </span>
                                    <div class="progress progress-md">
                                        @if($topicItem['total_questions'] > 0)
                                            <div class="progress-bar bg-success"
                                                 style="width:{{ ($topicItem['attempted_questions'] / $topicItem['total_questions']) * 100 }}%">
                                            </div>
                                        @else
                                            <div class="progress-bar bg-success" style="width:0%">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
@section('page-script')
    <style>
        .modal-content {
            border-radius: 10px;
        }

        .success_tic .page-body {
            max-width: 320px;
            background-color: #FFFFFF;
            margin: 10% auto;
        }

        .success_tic .head {
            text-align: center;
            color: #1ab394;
        }

        .success_tic .close {
            opacity: 1;
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 30px;
            color: #1ab394;
        }

        .success_tic .checkmark-circle {
            width: 150px;
            height: 150px;
            position: relative;
            display: inline-block;
            vertical-align: top;
        }

        .success_tic .checkmark-circle .background {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #1ab394;
            position: absolute;
        }

        .success_tic .checkmark-circle .checkmark {
            border-radius: 5px;
        }

        .success_tic .checkmark-circle .checkmark.draw:after {
            animation: checkmark 1s ease forwards;
            transform: scaleX(-1) rotate(135deg);
        }

        .success_tic .checkmark-circle .checkmark:after {
            opacity: 1;
            height: 75px;
            width: 37.5px;
            transform-origin: left top;
            border-right: 15px solid #fff;
            border-top: 15px solid #fff;
            content: '';
            left: 35px;
            top: 80px;
            position: absolute;
        }

        @keyframes checkmark {
            0% {
                height: 0;
                width: 0;
                opacity: 1;
            }
            20% {
                height: 0;
                width: 37.5px;
                opacity: 1;
            }
            40% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }
            100% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }
        }

        .failed_tic .page-body {
            max-width: 380px;
            margin: 10% auto;
        }

        .failed_tic .head {
            text-align: center;
            color: #c9302c;
        }


        .failed_tic .checkmark-circle {
            width: 150px;
            height: 150px;
            position: relative;
            display: inline-block;
        }

        .failed_tic .checkmark-circle .background {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #f8d7da; /* Light background for contrast */
            position: absolute;
        }

        .failed_tic .x-mark {
            position: relative;
            width: 70px; /* Adjust width */
            height: 70px; /* Adjust height */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .failed_tic .x-mark .line {
            position: absolute;
            height: 7px;
            width: 100%;
            background-color: #c9302c; /* Color of the "X" */
        }

        .failed_tic .x-mark .line.line1 {
            transform: rotate(45deg);
            top: 50%;
        }

        .failed_tic .x-mark .line.line2 {
            transform: rotate(-45deg);
            top: 50%;
        }

    </style>

    @if(auth()->check() && auth()->user()->role == 'student')
        <script>
            $(document).ready(function () {
                $("#submitPay").click(function () {
                    $(this).html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;&nbsp; Processing...`
                    );
                });
                @if(session('status'))
                    @if(session('status') == 'success')
                        $('#success_tic').modal('show');
                    @elseif(session('status') == 'failed')
                        $('#failed_tic').modal('show');
                    @else
                        $('#cancel_tic').modal('show');
                    @endif
                {{ session()->forget('status') }}
                @endif
            });
        </script>
        <div id="success_tic" class="modal fade success_tic" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a class="close" href="#" data-dismiss="modal">&times;</a>
                    <div class="page-body text-center">
                        <div class="head">
                            <h3>Payment Successful!</h3>
                        </div>
                        <h1>
                            <div class="checkmark-circle">
                                <div class="background"></div>
                                <div class="checkmark draw"></div>
                            </div>
                        </h1>
                        <br>
                        <h4>Your subscription is now active.</h4>
                        <h4>Thank you for choosing us!</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Your Failure Modal -->
        <div id="failed_tic" class="modal fade failed_tic" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a class="close" href="#" data-dismiss="modal" aria-label="Close"
                       style=" text-align: end; margin-right: 17px; margin-top: 10px; ">
                        <span aria-hidden="true">&times;</span>
                    </a>
                    <div class="page-body text-center">
                        <div class="head">
                            <h3>Payment Failed!</h3>
                        </div>
                        <h1>
                            <div class="checkmark-circle">
                                <div class="background"></div>
                                <div class="x-mark">
                                    <div class="line line1"></div>
                                    <div class="line line2"></div>
                                </div>
                            </div>
                        </h1>
                        <br>
                        <h4>Your subscription was not completed.</h4>
                        <h4>Please try again later.</h4>
                    </div>
                </div>
            </div>
        </div>
        <div id="cancel_tic" class="modal fade success_tic" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a class="close" href="#" data-dismiss="modal">&times;</a>
                    <div class="page-body text-center">
                        <div class="head">
                            <h3>Renewal Cancel Successful!</h3>
                        </div>
                        <h1>
                            <div class="checkmark-circle">
                                <div class="background"></div>
                                <div class="checkmark draw"></div>
                            </div>
                        </h1>
                        <br>
                        <h4>Your subscription is Deactivate After Plan End.</h4>
                        <h4>Thank you!</h4>
                    </div>
                </div>
            </div>
        </div>




    @endif


@endsection

