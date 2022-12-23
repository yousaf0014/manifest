import React, { Component } from 'react';
import { Link, browserHistory } from 'react-router';
import MyGlobleSetting from './MyGlobleSetting';
class TableRow extends Component {
  constructor(props) {
      super(props);
      this.handleSubmit = this.handleSubmit.bind(this);
  }
  handleSubmit(event) {
    event.preventDefault();
    let uri = MyGlobleSetting.url + `/api/delete/${this.props.obj.id}`;
    axios.delete(uri);
      browserHistory.push('/display-item');
  }
  render() {
    const obj = this.props.obj;
    return (
        <tr>
          <td>
            {obj.id}
          </td>
          <td>
            {obj.first_name}
          </td>
          <td>
            {obj.last_name}
          </td>
          <td>
            {obj.email}
          </td>
          <td>
          <form onSubmit={this.handleSubmit}>
            <Link to={"edit/"+obj.id} className="btn btn-primary">Edit</Link>
           <input type="submit" value="Delete" className="btn btn-danger"/>
         </form>
          </td>
        </tr>
    );
  }
}
export default TableRow;