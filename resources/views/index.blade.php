<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>芝麻信用认证</title>
    <link rel="stylesheet" href="{{ URL::asset('css/weui.min.css') }}">
    <script src="{{ URL::asset('js/zepto.min.js') }}"></script>
    <script src="{{ URL::asset('js/weui.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/layer_mobile/layer.js') }}"></script>

    <style>
        .title{text-align: center;margin-top: 2rem;}
        .image-wrap{display: flex;margin-top: 2rem;}
        .image-item{text-align: center;}
        .image-item img{width: 80%;height: 15rem}
        img.add-image{height: 3rem;width: 3rem;margin-top: 6rem;margin-bottom: 6rem}
        .desc{font-size: 1rem;color: #666;margin-top: 2rem;text-align: center}
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">芝麻信用认证</h2>
        <div class="image-wrap">
            <div class="image-item">
                <img src="{{ URL::asset('images/zmxy.jpg') }}" alt="">
                <button class="weui-btn weui-btn_disabled">图例</button>
            </div>
            <div class="image-item">
                <img src="{{ URL::asset('images/add_img.png') }}" id="add_img" class="add-image">
                <input type="file" id="file" style="display: none" onchange="preview()" accept="image/*">
                <button class="weui-btn weui-btn_primary" id="submit_btn">上传</button>
            </div>
        </div>
        <p class="desc">请上传刚刚支付宝授权的，如图例的额度图。</p>
    </div>
</body>
<script>
    // $('#add-img').removeClass('add-image');
    var $add_img = $('#add_img');
    var $file = $('#file');
    var $submit_btn = $('#submit_btn');
    var _token = '{{ csrf_token() }}';

    $add_img.click(function () {
        $file.click();
    });

    function preview() {
        var file = document.querySelector('#file').files[0];
        var reader = new FileReader();

        reader.onload = function () {
            document.querySelector('#add_img').src = reader.result;
            $add_img.removeClass('add-image')
        };
        if (file) {
            reader.readAsDataURL(file)
        }
    }

    $submit_btn.click(function () {
        var src = $add_img.attr('src');
        var file = $file[0].files[0];

        if (src.indexOf('add_img') > -1) {
            layer.open({
                content: '你还没有上传图片呢'
                ,skin: 'msg'
                ,time: 2
            });
            return;
        }

        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', _token);

        layer.open({
            type: 2
            ,content: '加载中'
            ,shadeClose: false
        });
        $.ajax({
            url: '{{ url('ocr') }}',
            type: 'POST',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                layer.closeAll();
                if (res.code === 200) {
                    layer.open({
                        content: res.msg
                        ,skin: 'msg'
                        ,time: 2
                    });
                } else {
                    layer.open({
                        content: res.msg
                        ,skin: 'msg'
                        ,time: 2
                    });
                }
            }
        })

    })




</script>
</html>
