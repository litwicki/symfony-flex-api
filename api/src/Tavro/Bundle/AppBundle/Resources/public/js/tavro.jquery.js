$(document).ready(function() {

    hljs.configure({useBR: true});

    $('div.code').each(function(i, block) {
      hljs.highlightBlock(block);
    });

});