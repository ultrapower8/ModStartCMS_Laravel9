<?php

namespace Module\Vendor\Html;

use ModStart\Core\Assets\AssetsUtil;

class SimpleEmotionConverterInterceptor implements HtmlConverterInterceptor
{
    public function convert($html)
    {
        $emotionList = [
            '[微笑]' => '01', '[撇嘴]' => '02', '[色]' => '03', '[发呆]' => '04', '[得意]' => '05', '[流泪]' => '06', '[害羞]' => '07', '[闭嘴]' => '08', '[睡]' => '09', '[大哭]' => '10', '[尴尬]' => '11', '[发怒]' => '12', '[调皮]' => '13', '[呲牙]' => '14', '[惊讶]' => '15', '[难过]' => '16', '[酷]' => '17', '[冷汗]' => '18', '[抓狂]' => '19', '[吐]' => '20', '[偷笑]' => '21', '[愉快]' => '22', '[白眼]' => '23', '[傲慢]' => '24', '[饥饿]' => '25', '[困]' => '26', '[惊恐]' => '27', '[流汗]' => '28', '[憨笑]' => '29', '[悠闲]' => '30', '[奋斗]' => '31', '[咒骂]' => '32', '[疑问]' => '33', '[嘘]' => '34', '[晕]' => '35', '[疯了]' => '36', '[衰]' => '37', '[骷髅]' => '38', '[敲打]' => '39', '[再见]' => '40', '[擦汗]' => '41', '[抠鼻]' => '42', '[鼓掌]' => '43', '[糗大了]' => '44', '[坏笑]' => '45', '[左哼哼]' => '46', '[右哼哼]' => '47', '[哈欠]' => '48', '[鄙视]' => '49', '[委屈]' => '50', '[快哭了]' => '51', '[阴险]' => '52', '[亲亲]' => '53', '[吓]' => '54', '[可怜]' => '55', '[菜刀]' => '56', '[西瓜]' => '57', '[啤酒]' => '58', '[篮球]' => '59', '[乒乓]' => '60', '[咖啡]' => '61', '[饭]' => '62', '[猪头]' => '63', '[玫瑰]' => '64', '[凋谢]' => '65', '[嘴唇]' => '66', '[爱心]' => '67', '[心碎]' => '68', '[蛋糕]' => '69', '[闪电]' => '70', '[炸弹]' => '71', '[刀]' => '72', '[足球]' => '73', '[瓢虫]' => '74', '[便便]' => '75', '[月亮]' => '76', '[太阳]' => '77', '[礼物]' => '78', '[拥抱]' => '79', '[强]' => '80', '[弱]' => '81', '[握手]' => '82', '[胜利]' => '83', '[抱拳]' => '84', '[勾引]' => '85', '[拳头]' => '86', '[差劲]' => '87', '[爱你]' => '88', '[NO]' => '89', '[OK]' => '90', '[爱情]' => '91', '[飞吻]' => '92', '[跳跳]' => '93', '[发抖]' => '94', '[怄火]' => '95', '[转圈]' => '96', '[磕头]' => '97', '[回头]' => '98', '[跳绳]' => '99', '[投降]' => '100', '[激动]' => '101', '[乱舞]' => '102', '[献吻]' => '103', '[左太极]' => '104', '[右太极]' => '105'
        ];
        foreach ($emotionList as $k => $v) {
            $html = str_replace($k, '<img class="emotion" src="' . AssetsUtil::fix("asset/image/emotion/$v@2x.png") . '" />', $html);
        }
        return $html;
    }

}
