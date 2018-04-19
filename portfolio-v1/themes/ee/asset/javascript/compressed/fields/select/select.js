"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var i=arguments[t];for(var n in i)Object.prototype.hasOwnProperty.call(i,n)&&(e[n]=i[n])}return e},_createClass=function(){function e(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,i,n){return i&&e(t.prototype,i),n&&e(t,n),t}}(),FilterableSelectList=makeFilterableComponent(SelectList),SelectField=function(e){function t(e){_classCallCheck(this,t);var i=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return i.selectionChanged=function(e){i.setState({selected:e})},i.setEditingMode=function(e){i.setState({editing:e})},i.handleRemove=function(e,t){e.preventDefault(),$(e.target).closest("[data-id]").trigger("select:removeItem",[t])},i.props.items=SelectList.formatItems(e.items),i.state={selected:SelectList.formatItems(e.selected,null,e.multi),editing:e.editing||!1},i}return _inherits(t,e),_createClass(t,[{key:"render",value:function(){var e=this,t=React.createElement(FilterableSelectList,_extends({},this.props,{selected:this.state.selected,selectionChanged:this.selectionChanged,tooMany:SelectList.countItems(this.props.items)>SelectList.defaultProps.tooManyLimit,reorderable:this.props.reorderable||this.state.editing,removable:this.props.removable||this.state.editing,handleRemove:function(t,i){return e.handleRemove(t,i)},editable:this.props.editable||this.state.editing}));return this.props.manageable?React.createElement("div",null,t,this.props.addLabel&&React.createElement("a",{"class":"btn action submit",rel:"add_new",href:"#"},this.props.addLabel),React.createElement(ToggleTools,{label:this.props.manageLabel},React.createElement(Toggle,{on:this.props.editing,handleToggle:function(t){return e.setEditingMode(t)}}))):t}}],[{key:"renderFields",value:function(e){$("div[data-select-react]",e).each(function(){var e=JSON.parse(window.atob($(this).data("selectReact")));e.name=$(this).data("inputValue"),ReactDOM.render(React.createElement(t,e,null),this)})}}]),t}(React.Component);$(document).ready(function(){SelectField.renderFields()}),Grid.bind("relationship","displaySettings",SelectField.renderFields),Grid.bind("file","displaySettings",SelectField.renderFields),Grid.bind("checkboxes","display",SelectField.renderFields),FluidField.on("checkboxes","add",SelectField.renderFields),Grid.bind("radio","display",SelectField.renderFields),FluidField.on("radio","add",SelectField.renderFields),Grid.bind("multi_select","display",SelectField.renderFields),FluidField.on("multi_select","add",SelectField.renderFields);