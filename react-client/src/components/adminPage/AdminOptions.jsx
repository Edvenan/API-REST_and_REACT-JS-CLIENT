import { useContext } from 'react';
import OptionButton from '../OptionButton';
import Swal from 'sweetalert2'
import AuthContext from '../../services/AuthContext';
import { toast } from 'react-toastify';
import axios from 'axios';
import foto from './../../images/green-background.jpg';

const AdminOptions = ({onOption, active, setActive}) => {
    
    const [api_urlRef,,, user, setUser,, tokenRef] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };
    const URL = api_urlRef.current;
    

    function handleClick(action){

        if (action !== 'refresh') setActive(action.slice(-4));
        
        if (action === "editName"){
            Swal.fire({
                title: "Edit user name",
                input: 'text',
                inputLabel: 'Admin Name',
                inputValue: user.name,
                showCancelButton: true,
                allowEnterKey:false,
                preConfirm: (inputValue) => {

                    // Http request
                    const bodyParameters = {
                        'name': inputValue
                     };
                     const id = toast.loading("Editing user name...");
                     axios.put(`${URL}/players/${user.id}`,bodyParameters, config).then(res => {
                        // change player's name
                        setUser(res.data.user);
                        // confirm player's name change
                          toast.update(id, {render:"User name edited successfully!", type:"success", isLoading: false, autoClose: 2000 });
                    }, (err) => {
                        toast.update(id, {render: "User name could not be edited!", type:"error", isLoading: false, autoClose: 3000 });
                    });
                    
                }
            }).then((result) => {
                setActive("Game");
              });         

        } else {
            onOption(action)
        }        
    }

    return (

        <div className="flex flex-col w-full h-full items-center justify-start bg-black px-2 border-4
                      border-yellow-300 my-0.5 mr-0.5 bg-no-repeat bg-cover bg-center" style={{  'backgroundImage': `url(${foto})` }}> 

            <h1 className="my-2 font-bold text-center text-yellow-300 text-xs sm:text-xl">OPTIONS</h1>

            <OptionButton value={"Ranking"} active={active}  onOptionClick={()=> handleClick("ranking")} />
            <OptionButton value={"Winner"} active={active}  onOptionClick={()=> handleClick("winner")}  />
            <OptionButton value={"Loser"}  active={active} onOptionClick={()=> handleClick("loser")}  />
            <OptionButton value={"Edit Name"} active={active}  onOptionClick={()=> handleClick("editName")} />
            <OptionButton value={"Refresh Data"} active={active} onOptionClick={()=> handleClick("refresh")} />
        </div>
    )
}

export default AdminOptions;
