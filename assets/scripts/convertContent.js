function convertContent(content) {
    let emojis = {
        ':)': '&#x1F601;',
        ':*': '&#x1F618;',
        'XD': '&#x1F606;',
        'xD': '&#x1F606;',
        'Xd': '&#x1F606;',
        'xd': '&#x1F606;',
        '<3': '&#x2764;',
        ';)': '&#x1F609;',
        ':P': '&#x1F60B;',
        ':p': '&#x1F60B;',
        ':D': '&#x1F603;',
        ':d': '&#x1F603;',
        ':(': '&#x1F61E;',
        ';(': '&#x1F622;',
        ':O': '&#x1F632;',
        ':o': '&#x1F632;'
    }
    let tag = ['<script>', '</script>', '<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<?php', '?>', '<?'];
    content = tags();
    content = marks("b");
    content = marks("i");
    content = marks("u");
    content = marks("code");
    content = emoji_easy();
    content = emoji();

    function tags() {
        let len = tag.length;
        for (let i = 0; i < len; i++) {
            tag_from = tag[i];
            while (content.includes(tag_from)) {
                content = content.replace(tag_from, '');
            }
        }
        return content;
    }

    function marks(mark) {
        do {
            content_temp = content;
            if (content.includes('[/' + mark + ']') && content.includes('[' + mark + ']')) {
                if (content.indexOf('[/' + mark + ']') < content.indexOf('[' + mark + ']')) {
                    content = content.replace('[/' + mark + ']', '');
                    continue;
                }
            }
            content = content.replace('[' + mark + ']', '<#' + mark + '>');
            if (content_temp != content) {
                if (content.includes('[/' + mark + ']') && content.includes('[' + mark + ']')) {
                    if (content.indexOf('[' + mark + ']') < content.indexOf('[/' + mark + ']')) {
                        content = content.replace('<#' + mark + '>', '');
                    } else {
                        content_temp_2 = content;
                        content = content.replace('[/' + mark + ']', '</' + mark + '>');
                        content = content.replace('<#' + mark + '>', '<' + mark + '>');
                    }
                } else if (content.includes('[/' + mark + ']')) {
                    content_temp_2 = content;
                    content = content.replace('[/' + mark + ']', '</' + mark + '>');
                    content = content.replace('<#' + mark + '>', '<' + mark + '>');
                } else {
                    content = content.replace('<#' + mark + '>', '');
                }
            } else {
                content = content.replace('[/' + mark + ']', '');
            }
        } while (content_temp != content);
        return content;
    }

    function emoji() {
        var index, emoji_true, emoji;
        do {
            content_temp = content;
            if (content.includes('U+')) {
                index = content.indexOf('U+');
                if (content[index + 2] == "1" && content[index + 3] == "F") {
                    emoji_true = '&#x1F';
                    emoji = 'U+1F';
                    for (var i = 4; i <= 6; i++) {
                        emoji_true += content[index + i];
                        emoji += content[index + i];
                    }
                    emoji_true += ';';
                    content = content.replace(emoji, emoji_true);
                } else {
                    emoji_true = '<span class="emoji">&#x';
                    emoji = 'U+';
                    for (var i = 2; i <= 5; i++) {
                        emoji_true += content[index + i];
                        emoji += content[index + i];
                    }
                    emoji_true += ';</span>';
                    content = content.replace(emoji, emoji_true);
                }
            }
        } while (content_temp != content);
        return content;
    }

    function emoji_easy() {
        let len = Object.keys(emojis).length;
        for (let i = 0; i < len; i++) {
            e_from = Object.keys(emojis)[i];
            e_to = '<span class="emoji">' + emojis[Object.keys(emojis)[i]] + '</span>';
            while (content.includes(e_from)) {
                content = content.replace(e_from, e_to);
            }
        }
        return content;
    }

    return content;
}
