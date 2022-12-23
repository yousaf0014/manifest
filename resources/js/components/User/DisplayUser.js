import React, {Component} from 'react';
import axios from 'axios';
import { Link } from 'react-router';
import TableRow from './TableRow';
import MyGlobleSetting from './MyGlobleSetting';
class DisplayUser extends Component {
  constructor(props) {
     super(props);
     this.state = {value: '', users: ''};
   }
  componentDidMount(){
    axios.get(MyGlobleSetting.url + '/api/users')
    .then(response => {
     this.setState({ users: response.data['data']['users']['data']});
    })
    .catch(function (error){
     console.log(error);
    })
  }
  tabRow(){
    if(this.state.users instanceof Array){
      return this.state.users.map(function(object, i){
          return <TableRow obj={object} key={object.id} />; 
      })
    }
  }
  render(){
    return (
      <div>
        <h1>Products</h1>
        <div className="row">
          <div className="col-md-10"></div>
          <div className="col-md-2">
            <Link to="/add-item">Create User</Link>
          </div>
        </div><br />
        <table className="table table-hover">
          <thead>
            <tr>
                <td>ID</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Email Name</td>
                <td width="200px">Actions</td>
            </tr>
          </thead>
          <tbody>
              {this.tabRow()}
          </tbody>
        </table>
      </div>
    )
  }
}
export default DisplayUser;