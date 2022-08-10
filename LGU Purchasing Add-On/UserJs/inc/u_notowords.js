// System for American Numbering 
var th_val = ['', 'THOUSAND', 'MILLION', 'BILLION', 'TRILLION'];
// System for uncomment this line for Number of English 
// var th_val = ['','thousand','million', 'milliard','billion'];
 
var dg_val = ['ZERO', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE'];
var tn_val = ['TEN', 'ELEVEN', 'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'];
var tw_val = ['TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'];
function toWordsconver(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s))
        return 'Not a Number ';
    var x_val = s.indexOf('.');
    if (x_val == -1)
        x_val = s.length;
    if (x_val > 15)
        return 'Too Big';
    var n_val = s.split('');
    var str_val = '';
    var sk_val = 0;
    for (var i = 0; i < x_val; i++) {
        if ((x_val - i) % 3 == 2) {
            if (n_val[i] == '1') {
                str_val += tn_val[Number(n_val[i + 1])] + ' ';
                i++;
                sk_val = 1;
            } else if (n_val[i] != 0) {
                str_val += tw_val[n_val[i] - 2] + ' ';
                sk_val = 1;
            }
        } else if (n_val[i] != 0) {
            str_val += dg_val[n_val[i]] + ' ';
            if ((x_val - i) % 3 == 0)
                str_val += 'HUNDRED ';
            sk_val = 1;
        }
        if ((x_val - i) % 3 == 1) {
            if (sk_val)
                str_val += th_val[(x_val - i - 1) / 3] + ' ';
            sk_val = 0;
        }
    }
    if (x_val != s.length) {
        var y_val = s.length;
        str_val += 'AND ';
        for (var i = x_val + 1; i < y_val; i++) {
            str_val += dg_val[n_val[i]] + ' ';
        }
        str_val += ' CENTS';
    }
    return str_val.replace(/\s+/g, ' ');
}