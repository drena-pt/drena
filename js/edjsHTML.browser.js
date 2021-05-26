var edjsHTML = (function () {
  "use strict";
  var t = {
    delimiter: function () {
      return "<br/>";
    },
    header: function (t) {
      var e = t.data;
      return "<h" + e.level + "> " + e.text + " </h" + e.level + ">";
    },
    paragraph: function (t) {
      return "<p> " + t.data.text + " </p>";
    },
    list: function (t) {
      var e = t.data,
        n = "unordered" === e.style ? "ul" : "ol",
        r = "";
      return (
        e.items &&
          (r = e.items
            .map(function (t) {
              return "<li> " + t + " </li>";
            })
            .reduce(function (t, e) {
              return t + e;
            }, "")),
        "<" + n + "> " + r + " </" + n + ">"
      );
    },
    image: function (t) {
      var e = t.data,
        n = e.caption ? e.caption : "Image";
      return '<img data-toggle="tooltip" data-placement="bottom" class="shadow mb-2 mx-auto d-block col" src="' + (e.file ? e.file.url : "") + '" data-original-title="' + n + '" />';
      },
    quote: function (t) {
      var e = t.data;
      return "<blockquote> " + e.text + " </blockquote> - " + e.caption;
    },
    embed: function (t) {
      var e = t.data;
      switch(e.service) {
        case 'youtube':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2 shadow' width='"+e.width+"' height='"+e.height+"' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
          break;
        case 'twitter':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2' width='"+e.width+"' height='"+(e.height)*2+"' style='margin: 0 auto;' scrolling='no' frameborder='0' allowtransparency='true' allowfullscreen='true'></iframe>";
          break;
        case 'instagram':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2 shadow' width='"+e.width+"' height='"+e.height+"'></iframe>";
          break;
        case 'drena':
          return "<div class='mx-auto d-block col mb-2 shadow' style='position:relative;padding-bottom:56.25%;'><iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='"+e.embed+"' scrolling='no' frameborder='no' allowtransparency='true' allowfullscreen='true'></iframe></div>";
          break;
        default:
          return "<a href='"+e.source+"'>"+e.source+"</a>";
          break;
      }
    },
  };
  function e(t) {
    return new Error(
      '[31m The Parser function of type "' +
        t +
        '" is not defined. \n\n  Define your custom parser functions as: [34mhttps://github.com/pavittarx/editorjs-html#extend-for-custom-blocks [0m'
    );
  }
  return function (n) {
    return (
      void 0 === n && (n = {}),
      Object.assign(t, n),
      {
        parse: function (n) {
          return n.blocks.map(function (n) {
            return t[n.type] ? t[n.type](n) : e(n.type);
          });
        },
        parseBlock: function (n) {
          return t[n.type] ? t[n.type](n) : e(n.type);
        },
      }
    );
  };
})();
