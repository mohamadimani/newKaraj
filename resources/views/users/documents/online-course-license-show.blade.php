<style>
    #printable * {
        font-size: 18px !important;
    }

    p {
        font-family: "B YEKAN";
    }

    .f1-5 {
        font-size: 1.5vw;
        font-weight: bold;
        color: #E2AC48;
    }

    .container1 {
        /*display: none;*/
        position: relative;
    }

    .center {
        position: absolute;
        top: 53.4%;
        width: 100%;
        text-align: center;
    }

    .imgCover {
        width: 100%;
        height: auto;
    }

    .center p {
        position: absolute;
        text-align: right;
    }

    .name {
        right: 20%;
        top: -14px
    }

    .father {
        top: -14px;
        right: 43.5%;
    }

    .pas {
        right: 64%;
        top: -14px;
    }

    .mt-4-5 {
        margin-top: 4.3%;
    }

    .course {
        top: 5px;
        right: 23%
    }

    .time {
        right: 54.5%
    }

    .start {
        right: 68%;
    }

    .end {
        right: 82%
    }

    .center2 {
        position: absolute;
        top: 19.6%;
        left: 15.8%;
        width: 100%;
    }

    .profile {
        position: inherit;
        height: auto;
        width: 9.6%;
        left: -0.9%;
    }

    #back {
        position: fixed;
        top: 90vh;
        left: 20vw;
        opacity: 0.8;
        color: #fff;
        background-color: #175db8;
        border-color: #175db8;
        padding: 8px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none
    }

    #print {
        position: fixed;
        top: 90vh;
        right: 20vw;
        opacity: 0.8;
        color: #fff;
        background-color: #17b857;
        border-color: #17b855;
        padding: 8px;
        border-radius: 5px;
        cursor: pointer
    }

    #print:hover {
        opacity: 1;
    }

    #back:hover {
        opacity: 1;
    }

    @media print {

        header,
        footer,
        #back,
        #print {
            display: none;
        }

        .container1 {
            display: block;
            margin-top: 0px;
            transform: rotate(90deg);
        }

        .imgCover {
            width: 141.4%;
        }

        .profile {
            height: auto;
            width: 13.4%;
            left: 5.4%;
            position: inherit;
        }

        .center {
            top: 53.5%;
        }

        .name {
            right: -16%;
        }

        .father {
            right: 20.5%;
        }

        .pas {
            right: 47%;
        }

        .mt-4-5 {
            margin-top: 5.9%;
        }

        .course {
            right: -9%;
        }

        .time {
            right: 35%;
        }

        .start {
            right: 54%;
        }

        .end {
            right: 75%;
        }

        .f1-5 {
            font-size: 2.8vw;
            font-weight: bold;
        }

    }

    @media screen and (max-width: 790px) {
        #print {
            position: fixed;
            top: 100vw;
            right: 25%;
            opacity: 0.8;
            font-size: 15px;
        }

        #back {
            position: fixed;
            top: 100vw;
            left: 17%;
            opacity: 0.8;
            font-size: 15px;
        }
    }
</style>
<div id="printable" class="container1">
    <img class="imgCover" src="{{GetImage('documents/document.png')}}" alt="newdeniz">
    <div class="center">
        <p class="name f1-5">{{user()->full_name}}</p>
        <p class="father f1-5">{{user()->student->father_name}}</p>
        <p class="pas f1-5">{{user()->student->national_code}}</p>
        <p class="course mt-4-5 f1-5">{{$orderItem->onlineCourse->name}}</p>
        <p class="time mt-4-5 f1-5">{{$orderItem->onlineCourse->duration_hour}}</p>
        <p class="start mt-4-5 f1-5">{{georgianToJalali($orderItem->created_at)}}</p>
        <p class="end mt-4-5 f1-5">-</p>
    </div>
    <div class="center2">
        <img class="profile" src="{{GetImage('students/personal/' . user()->student->personal_image) ?? GetImage('images/admin/default.png')}}" alt="newdeniz">
    </div>

</div>
<div class="modal-footer">
    <a class="btn btn-success" id="print" onclick="window.print();">چاپ مدرک</a>
    <a href="{{ route('user.documents.course-license') }}" class="btn btn-secondary" id="back">بازگشت</a>

</div>
