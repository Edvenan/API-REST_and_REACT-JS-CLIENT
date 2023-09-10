import { useContext, useState } from 'react';
import OptionButton from '../OptionButton';
import Swal from 'sweetalert2'
import AuthContext from '../../services/AuthContext';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import axios from 'axios';
import foto from './../../images/green-background.jpg';

export default function PlayerOptions () {
    
    const [api_urlRef,,, user, setUser,, tokenRef,, setGamesList,, setWinsRate] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };

    const [active, setActive] = useState("Game");
    const URL = api_urlRef.current;

    function handleClick(action) {
        setActive(action.slice(-4));

        if (action === "editName"){
            Swal.fire({
                title: "Edit player's name",
                input: 'text',
                inputLabel: 'Player Name',
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
            
                       
        } else if  (action === "deleteGames"){
            Swal.fire({
                title: 'Delete all your games!\nAre you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete my games!',
                allowEnterKey:false,
              }).then((result) => {
                if (result.isConfirmed) {
                    // Http request
                     const id = toast.loading("Deleting player games...");
                     axios.delete(`${URL}/players/${user.id}/games`,config).then(res => {
                        // deleted player's games
                        setGamesList(['nf']);
                        setWinsRate(0);
                        // confirm game list deletion
                        toast.update(id, {render:"Player games deleted successfully!", type:"success", isLoading: false, autoClose: 2000 });
                    }, (err) => {
                        toast.update(id, {render: "Player games could not be deleted!", type:"error", isLoading: false, autoClose: 3000 });
                    });
                }
                setActive("Game");
              })
        }
    }

    return (
        <div className="flex flex-col w-full h-full items-center justify-start bg-black px-2 border-4
                    border-yellow-300 my-0.5 mr-0.5 bg-no-repeat bg-cover bg-top" style={{  'backgroundImage': `url(${foto})` }}> 

            <h1 className="my-2 font-bold text-center text-yellow-300 text-xs sm:text-xl">OPTIONS</h1>

            <OptionButton value={"Edit Name"} active={active}  onOptionClick={()=> handleClick("editName")} />
            <OptionButton value={"Delete Games"} active={active}  onOptionClick={()=> handleClick("deleteGames")} />
        </div>
    )
}