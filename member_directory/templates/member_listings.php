<style type="text/css">
html {
  box-sizing: border-box;
}
*, *:before, *:after {
  box-sizing: inherit;
}

.member_tile {
    position: relative;
    border-top: 1px solid #e1e1e1;
    margin: 0 0 24px 0;
    border-radius: 3px;
}

.member_tile h2 {
    margin: 14px 0 16px 0;
}

.member_tile p {
    margin: 0 0 14px 0;
}

.member_tile p.info {
    font-size: 0.8em;
    color: #666;
}

.member_tile img {
    border-radius: 200px;
    margin-top: -24px;
    margin-left: -32px;
}

.listing_right {
    margin-left: 85px;
}

.type_3 {
    width: 100%;
    border-top: 6px solid #fbb03b;
    border-left: 1px solid #fbb03b;
    border-right: 1px solid #fbb03b;
    border-bottom: 1px solid #fbb03b;
    background: url('/custom/plugins/member_directory/medal.png') right -12px no-repeat;
}

.type_3 img {
    border: 1px solid #fbb03b;
    width: 100px;
    height: 100px;
    margin-top: -24px;
    margin-left: -42px;
}

.type_2 {
    clear: both;
    width: 100%;
    float: none;
    border-top: 6px solid #e1e1e1;
    border-left: 1px solid #e1e1e1;
    border-right: 1px solid #e1e1e1;
    border-bottom: 1px solid #e1e1e1;
    background: url('/custom/plugins/member_directory/medal1.png') right -12px no-repeat;
}

.type_2 img {
    border-top: 3px solid #e1e1e1;
    border: 1px solid #e1e1e1;
    width: 80px;
    height: 80px;
    margin-top: -32px;
    margin-left: -42px;
}

.type_1 {
    border: 1px solid #f9f9f9;
    /*background: url('/custom/plugins/member_directory/medal2.png') right -12px no-repeat;*/
}

.type_1 img {
    border-top: 3px solid #f9f9f9;
    border: 1px solid #f9f9f9;
}

</style>

    <h1>Member Directory</h1>

    <div id="zen_content" class="zen_fonts">
        <div class="zen_pad_more">

            <form action="%pp_url%/directory" method="get">

                <div class="zen_section_left">
                    Search: <input type="text" name="query" value="%query%" /> <input type="submit" value="Search" />
                </div>
                <div class="zen_section_right">
                    %pagination%
                </div>
                <div class="zen_clear"></div>

            </form>

            <div style="height:24px;"></div>

            %entries%
            <div class="zen_clear"></div>

            <form action="%pp_url%/directory" method="get">

                <div class="zen_section_left">
                    &nbsp;
                </div>
                <div class="zen_section_right">
                    %pagination%
                </div>
                <div class="zen_clear"></div>

            </form>

        </div>
    </div>
		