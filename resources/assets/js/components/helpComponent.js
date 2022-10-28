var React = require('react');
var ReactDOM = require('react-dom');


var HelpComponent = React.createClass({

    render: function() {

        return(

                <div>
                    <h1>this is a component</h1>
                </div>

        );
    },

});


ReactDOM.render(<HelpComponent/>, document.getElementById('help_wrapper'));