                    <!-- offcanvas-left -->
                <div id="cnt_menu">
                    <div class="offcanvas_left side-base print-none">
                        <nav class="">
                        <div id="menu_li">
                            <!--<h3 class="side-head p-3 font-size-rg">在庫管理システム</h3>-->
                                <ul>
                                        <li class="gc1"><a class="" href="{{ url('/regi') }}">伝票番号登録</a></li>
                                        <li class="gc1"><a class="" href="{{ url('/process') }}">作業工程作成</a></li>
                                        <li class="gc1"><a class="" href="{{ url('/view') }}">作業工程閲覧</a></li>
                                        <!--<li class="gc1"><a class="" href="{{ url('/schedule') }}">スケジュール</a></li>-->
                                        <li class="gc1"><a class="" href="{{ url('/home') }}">マニュアル</a></li>
                                        <li class="gc1"><a class="" href="" onClick="return false;"></a></li>
                                        <!--
                                        <li><a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img class="iconsize_sm" src="{{ asset('images/round-lock-w.svg') }}" alt="">ログアウト</a></li>
                                        -->
                                </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            </form>
                        </div>
                        </nav>
                    </div>
                </div>
                    <!-- /offcanvas-left -->
