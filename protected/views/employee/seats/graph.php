<style>
    .graph_container {
        width:100%;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        position: relative;
    }
    .graph_item {
        margin:10px;
        width: 440px;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
    }
    .item_title {
        width: 439px;
        height: 50px;
        margin:0 0 -1px -1px;
        box-sizing: border-box;
        border: 1px solid grey;
        padding: 15px 5px 5px 5px;
        text-align: center;
    }
    .graph_item .item_content {
        border: 1px solid grey;
        position: relative;
        width:220px;
        height: 180px;
        box-sizing: border-box;
        margin:0 0 -1px -1px;
    }
    .inner_item_content {
        border: 1px solid grey;
        position: relative;
        width:220px;
        height: 180px;
        box-sizing: border-box;
        border-top: none;
        margin: 0 0 0 -1px;
    }
    .graph_item .merge_content {
        height: 720px;
        border-top: none;
    }
    .item_content_background {
        width:220px;
        height: 180px;
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .item_content_background div {
        font-size: 60px;
        z-index: 100;
        opacity: .2;
    }
    .item_content_info {
        position: absolute;

        padding: 10px 0 0 10px;
        z-index: 200;
    }
    .item_content_info p {
        margin: 5px;
        color: #333;
    }

    .graph_item_single {
        margin:10px;
        width:220px;
        height: 765px;
    }
    .graph_item_single .item_title {
        width:220px;
        height: 50px;
        margin:0 0 -1px -1px;
        box-sizing: border-box;
        border: 1px solid grey;
    }
    .graph_item_single .item_content {
        border: 1px solid grey;
        position: relative;
        width:220px;
        height: 715px;
        box-sizing: border-box;
        margin:0 0 -1px -1px;
    }
    .graph_container .door_container {
        width: 440px;
        position: absolute;
        top:0;
    }
    .door_container .door_bar {
        background: #333;
        width: 400px;
        height: 15px;
        margin: 10px auto 5px auto;
    }
    .door_container h2 {
        color: #333;
        margin: auto;
        text-align: center;
    }
</style>
<div>
    <h3>員工座位圖</h3>
    <div class="graph_container">
        <div class="door_container">
            <div class="door_bar"></div>
            <h2>大門口</h2>
        </div>
        <div class="graph_item">
            <div class="item_title">圖資管理部 外線:2343-2131</div>
            <?php foreach($data['圖資管理部 外線:2343-2131'] as $item):?>
            <div class="item_content">
                <div class="item_content_background">
                    <div><?=$item['seat_number']?></div>
                </div>
                <div class="item_content_info">
                    <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                    <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                    <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                    <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                    <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                    <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="graph_item">
            <div class="item_title">編輯部 外線:2343-3141、3143</div>
            <?php foreach($data['編輯部 外線:2343-3141、3143'] as $item):?>
                <div class="item_content">
                    <div class="item_content_background">
                        <div><?=$item['seat_number']?></div>
                    </div>
                    <div class="item_content_info">
                        <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                        <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                        <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                        <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                        <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                        <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <div class="graph_item_single"></div>
        <div class="graph_item">
            <div class="item_title">資料管理部 外線:2343-2131</div>
            <div class="item_content merge_content">

                <?php foreach($data['資料管理部 外線:2343-2131'] as $index => $item):?>
                    <?php if($index % 2 === 0):?>
                        <div class="inner_item_content">
                            <div class="item_content_background">
                                <div><?=$item['seat_number']?></div>
                            </div>
                            <div class="item_content_info">
                                <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                                <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                                <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                                <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                                <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                                <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>

            <div class="item_content merge_content">

                <?php foreach($data['資料管理部 外線:2343-2131'] as $index => $item):?>
                    <?php if($index % 2 !== 0):?>
                        <div class="inner_item_content" <?php if($index === 1):?> style="height:360px"<?php endif;?>>
                            <div class="item_content_background">
                                <div><?=$item['seat_number']?></div>
                            </div>
                            <div class="item_content_info">
                                <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                                <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                                <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                                <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                                <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                                <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>

        <div class="graph_item">
            <div class="item_title">專案管理部 外線:2343-3146</div>
            <?php foreach($data['專案管理部 外線:2343-3146'] as $item):?>
                <div class="item_content">
                    <div class="item_content_background">
                        <div><?=$item['seat_number']?></div>
                    </div>
                    <div class="item_content_info">
                        <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                        <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                        <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                        <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                        <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                        <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>

        <div class="graph_item_single">
            <div class="item_title">董事長 專線:2343-3145</div>
            <div class="item_content item_single_content">
                <div class="item_content_background">
                    <div></div>
                </div>
                <?php foreach($data['董事長 專線:2343-3145'] as $item):?>
                <div class="item_content_info">
                    <?php if(!empty($item['name'])):?><p>姓名:<?=$item['name']?></p><?php endif;?>
                    <?php if(!empty($item['position'])):?><p>職務:<?=$item['position']?></p><?php endif;?>
                    <?php if(!empty($item['ext_number'])):?><p>分機:<?=$item['ext_number']?></p><?php endif;?>
                    <?php if(!empty($item['mobile'])):?><p>手機:<?=$item['mobile']?></p><?php endif;?>
                    <?php if(!empty($item['email'])):?><p>E-Mail:<?=$item['email']?></p><?php endif;?>
                    <?php if(!empty($item['memo'])):?><p>備註:<?=$item['memo']?></p><?php endif;?>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>