import React, { useState, useContext } from 'react';
import './RollDice.css';
import Die from './Die';
import axios from 'axios';
import AuthContext from '../../../services/AuthContext';
import { toast } from 'react-toastify';
import Swal from 'sweetalert2';
import rolling_sound from '../../../audio/dices.mp3';
import win_sound from '../../../audio/win-sound.mp3';
import lose_sound from '../../../audio/lose-sound.mp3';

const RollDice = ({ sides }) => {
	const [api_urlRef,,,user,,,tokenRef,,,,setWinsRate,,,,,,, refresh, setRefresh] = useContext(AuthContext);
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };
	const URL = api_urlRef.current;

	const [die1, setDie1] = useState('one');
	const [die2, setDie2] = useState('one');
	const [rolling, setRolling] = useState(false);

	function play_sound(type) {
		var audio = new Audio();
		/* Audio link for notification */
		switch (type) {
			case 'roll':
				audio = new Audio(rolling_sound);
				break;
			case 'win':
				audio = new Audio(win_sound);
			break;
			case 'lose':
				audio = new Audio(lose_sound);
			break;
			default:
				break;
		}
		audio.play();
	}

	const roll = () => {
		setRolling(true);
		play_sound('roll');
		// play game
		setTimeout(()=> {
			axios.post(`${URL}/players/${user.id}/games`,[],config).then(res => {

				const randomIndex1 = res.data.Game.dice_1 -1;
				const randomIndex2 = res.data.Game.dice_2 -1;
				setDie1(die1 => sides[randomIndex1]);
				setDie2(die2 => sides[randomIndex2]);
				setRolling(rolling => false);
				setWinsRate(winsRate => res.data.WinsRate);
				if (res.data.Game.result){
					let timerInterval
					Swal.fire({
						title: `${res.data.Game.dice_1} + ${res.data.Game.dice_2} = ${res.data.Game.dice_1 + res.data.Game.dice_2}\n You win!`,
						target: '#result-modal',
						position: "top",
						backdrop: false,
						customClass: {
							container: 'position-absolute'
							},
						icon: 'success',
						width: 300,
						timer: 2000,
						timerProgressBar: true,
						showConfirmButton: false,
						didOpen: () => {play_sound('win');},
						willClose: () => {
							clearInterval(timerInterval)
						}
					})
				} else {
					let timerInterval
					Swal.fire({
						title: `${res.data.Game.dice_1} + ${res.data.Game.dice_2} = ${res.data.Game.dice_1 + res.data.Game.dice_2} \n You Lose!`,
						target: '#result-modal',
						position: "top",
						backdrop: false,
						customClass: {
							container: 'position-absolute'
							},
						icon: 'error',
						timer: 2000,
						timerProgressBar: true,
						showConfirmButton: false,
						width: 300,
						didOpen: () => {play_sound('lose');},
						willClose: () => {
							clearInterval(timerInterval)
						}
					})
				}
				setRefresh(!refresh);
			}, (err) => {
				toast.error("Could not roll!", {theme:"coloured", autoClose: 3000 });
			});
		},1500);
	
	};

	const handleBtn = rolling ? 'RollDice-rolling' : '';

	return (
		<div className='RollDice flex flex-grow w-full'>
		<div className='RollDice-container'>
			<Die face={die1} rolling={rolling} />
			<Die face={die2} rolling={rolling} />
		</div>
		
		<div id='result-modal' className='flex flex-col flex-grow items-center relative w-full '>
			<button className={handleBtn} disabled={rolling} onClick={roll}>
				{rolling ? 'Rolling' : 'Roll!'}
			</button>
		</div>
		
		</div>
		
	);
};

RollDice.defaultProps = {
  sides: ['one', 'two', 'three', 'four', 'five', 'six']
};

export default RollDice;
