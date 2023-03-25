<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        .table td,
        .table th {
            padding: 2px 5px;
            /* adjust the padding as needed */
        }

        .table .services {
            max-width: 120px;
            word-wrap: break-word;

        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @for ($i = 0; $i < 10; $i++)
        @if ($i % 3 == 0 && $i != 0)
            <div class="page-break"></div>
        @endif
        <div class="main-container"
            style="border-top: 1px solid; border-bottom: 1px solid; font-size: 12px">
            <div class="pd-ltr-20 xs-pd-20-10">
                <div class="min-height-200px">
                    <!-- basic table  Start -->
                    <div class="pd-20 card-box mb-30">
                        <div class="clearfix mb-10">
                            <div class="float-left" style="font-size: 9px">
                                <strong>Can Ho mini Hoan Hao 2</strong>
                                <p><strong>Address: </strong> 225/12/47 duong 30/4 phuong Hung Loi, Ninh Kieu, Can Tho
                                </p>
                            </div>
                            <div class="float-right">
                                <strong style="font-size: 14px">Hoa Don Tien Phong</strong>
                            </div>
                        </div>

                        <div class="clearfix mb-20">
                            <div class="float-left">
                                <span><strong>Name: </strong>Luu Hoai Phong</span><br>
                                <span><strong>Phone: </strong>0398371050</span><br>
                                <span><strong>Email: </strong>luuhoaiphong147@gmail.com</span>
                            </div>
                            <div class="float-right">
                                <span><strong>Room: </strong>14B</span><br>
                                <span><strong>Date in: </strong>March 01, 2003</span><br>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"></th>
                                    <th scope="col" class="text-center">Consumed</th>
                                    <th scope="col" class="text-center">Unit price</th>
                                    <th scope="col" class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td class="services">Electricity (Old: 12345, New: 12345)</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">100000</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td class="services">Electricity (Old: 12345, New: 12345)</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">100000</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td class="services">Electricity (Old: 12345, New: 12345)</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">100000</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td class="services">Electricity (Old: 12345, New: 12345)</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">100000</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td class="services">Electricity (Old: 12345, New: 12345)</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">100000</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-center font-weight-bold">Total</td>
                                    <td class="text-center font-weight-bold font-14">500000</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- basic table  End -->
                </div>
            </div>
        </div>
    @endfor
</body>

</html>


{{-- <div style="border: 1px solid; width: 100%; padding: 10px">
    <div style="font-size: 10px">
        <span>
            <strong>Can Ho Mini Hoan Hao 2</strong>
        </span>
        <span style="float: right">225/12/47 duong 30/4 Hung Loi, Ninh Kieu, Can Tho</span>
    </div>
    <div style="margin-top: -10px">
        <h5 style="text-align:center"><strong>Hoa don tien nha - Thang 3 nam 2023</strong></h5>
    </div>
    <div style="font-size: 12px; margin-top: -30px">
        <div>
            <span><strong>Name: </strong>Luu Hoai Phong</span>
            <span style="float: right"><strong>Phone: </strong>0123456789</span>
        </div>
        <div>
            <span><strong>Address: </strong>225/12/47 duong 30/4 Hung Loi, Ninh Kieu, Can Tho</span>
            <span style="float: right"><strong>Room: </strong>101</span>
        </div>
    </div>
    <div style="border-bottom: 2px solid black; border-top: 2px solid black">
        <table width="100%" style="border: 1px solid;">
            <thead>
                <tr>

                    <th></th>
                    <th>Consumed</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Electricity (Old: 12345 - New: 12345)</td>
                    <td style="text-align: center">100</td>
                    <td style="text-align: center">1000</td>
                    <td style="text-align: center">100000</td>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <td style="text-align: center">100</td>
                    <td style="text-align: center">1000</td>
                    <td style="text-align: center">100000</td>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <td style="text-align: center">100</td>
                    <td style="text-align: center">1000</td>
                    <td style="text-align: center">100000</td>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <td style="text-align: center">100</td>
                    <td style="text-align: center">1000</td>
                    <td style="text-align: center">100000</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="border-bottom: 2px solid black">
        <h3><strong>TỔNG CỘNG</strong><strong style="float:right">@SumAmount</strong></h3>
    </div>
    <div>
        <span style="float:left" name="textKyTen">
            <strong>Người thanh toán</strong></span><span style="float:right" name="textKyTen"><strong>Người nhận
                TT</strong>
        </span>
    </div>
    <br>
</div> --}}
