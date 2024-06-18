<!DOCTYPE html>
<html lang="en">
<style>
    * {
        font-family: DejaVu Sans !important;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            margin-top: 20px;
            background-color: #eee;
        }

        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-15">Đơn Hàng #202405100001</h4>
                            <div class="mb-4">
                                <h2 class="mb-1 text-muted">WOODPRO</h2>
                            </div>
                            <div class="text-muted" style="font-size: 12px;">
                                <p class="mb-1">90-92 Đường B2, Khu đô
                                    thị Sala, An Lợi Đông, Thủ Đức, HCM</p>
                                <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i> info@gmail.com</p>
                                <p><i class="uil uil-phone me-1"></i> 0987.654.321</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div>
                                <div class="text-muted" style="font-size: 10px;">
                                    <h5 class="mb-3">Thông tin khách hàng:</h5>
                                    <div>
                                        <h5 class="mb-2">Nguyễn Thanh Sơn</h5>
                                        <p class="mb-1">Số 1, Phố Xốm, Phú Lãm, Hà Đông, Hà Nội</p>
                                        <p>0984768820</p>
                                    </div>
                                    <div class="float-end" style="font-size: 10px;">
                                        <div class="text-muted text-end">
                                            <div class="mt-4">
                                                <h5 class="mb-1">Ngày đặt hàng:</h5>
                                                <p>09-05-2020</p>
                                            </div>
                                        </div>
                                        <div class="text-muted text-end">
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Ngày in hóa đơn:</h5>
                                                <p>09-05-2020</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end row -->

                        <div class="py-2">
                            <h5 class="font-size-15">Thông tin đơn hàng</h5>
                            @for ($i = 0; $i < 3; $i++) <div class="row mt-2">
                                <div>{{ $i+1 }}. Sofa Góc Phòng Khách Gỗ Sồi Sơn Màu Óc Chó Đẹp Giá Rẻ</div>
                                <div class="row">
                                    <div class="col-sm-3 mt-2" style="margin-left: 14px;">Màu sắc: Trắng</div>
                                    <div class="col-sm-3 mt-2" style="margin-left: 14px;">Kích thước: 100 x 100 x 100</div>
                                    <div class="col-sm-3 mt-2" style="margin-left: 14px;">Chất liệu: MDF cao cấp</div>
                                </div>
                                <div class="row mt-2" style="margin-left: 1px;">
                                    <div class="col-6">Số lượng: 1</div>
                                    <div class="col-6 text-end">Giá: <span class="fw-bold">12.999.999đ</span></div>
                                </div>
                        </div>
                        @endfor
                        <div class="row mt-5">
                            <div class="col-7 offset-5 text-end">
                                <p style="padding-right: 11px;">Tổng tiền: <span class="fs-2 fw-bold" style="margin-left: 10px;">50.000.000đ</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-5 text-center">Cảm ơn đã mua sản phẩm của chúng tôi</p>
            </div>
        </div><!-- end col -->
    </div>
    </div>
</body>

</html>