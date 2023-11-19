<style>

	main {
		display: flex;
		flex-direction: column;
	}

	#credits {
		max-height: 80vh;
		width: 100%;
		flex:1;

		scroll-behavior: smooth;

		overflow: hidden;

	}

	#credits > :last-child {
		margin-top: 40vh;
		margin-bottom: 65vh;
	}

	#credits .title {
		position: sticky;
		top: 0;

		padding-top: 2rem;
		padding-bottom: 2rem;

		background: linear-gradient(to top, #ffffff00, var(--background) 2rem);

		display: flex;
		align-items: end;
		gap: 0.5rem;
	}

	@media screen and (prefers-color-scheme: dark) {
		#credits .title {
            background: linear-gradient(to top, #12121200, var(--background-dark) 2rem);
		}
	}

	#credits .title > * {
		margin: 0;
	}

	#credits .spacer {
		display: block;
		/*position: relative;*/
		height: 65vh;

		transition: height 0.5s ease-in-out;
	}

	#fps {
		position: fixed;
		bottom: .5rem;
		left: .5rem;

		font-family: monospace;
	}

	.fa-ul {
		position: relative;
		z-index: -10;
	}
</style>

<div id="fps"></div>

<div id="credits">
	<div class="title">
		<h1 class="logo"><span class="bc-1">Bye</span><span class="bc-2">Corps</span><span class="bc-3"> ID</span></h1>
		<span class="subtitle">v. <?= current_git_commit() ?></span>
	</div>

	<p>ByeCorps ID is a <a href="https://byecorps.com">ByeCorps</a> service created by <a href="https://byemc.xyz">Bye</a>. It wouldn't be possible without the work of other amazing people.</p>
	<button id="start">Show credits.</button>

	<div class="spacer"></div>

	<h2>Credits</h2>
	<ul>
		<li><a href="https://bye.omg.lol">Bye</a>, who programmed this entire thing.</li>
		<li><a href="https://adam.omg.lol">Adam Newbold</a> for writing the code this site depends on for routing</li>
		<li>PHP, the language it's built in.</li>
		<li>Composer, to keep all the libs together.</li>
		<li>Caddy, the webserver it's usually running on.</li>
		<li>MariaDB, the MySQL server.</li>
		<li>PhpStorm by JetBrains and Visual Studio Code by Microsoft, both of which were used to make this service.</li>
	</ul>

	<h2>Music</h2>
	<ul class="fa-ul music">
		<li><span class="fa-li fa-fw fa-solid fa-compact-disc fa-spin"></span> <strong>Now playing</strong>:<br>"Screen Saver" Kevin MacLeod (<a href="https://incompetech.com">incompetech.com</a>)<br>
			Licensed under Creative Commons: By Attribution 4.0 License<br>
			<a href="http://creativecommons.org/licenses/by/4.0/">http://creativecommons.org/licenses/by/4.0/</a></li>
		<li><span class="fa-li fa-fw fa-solid fa-music"></span>"Electrodoodle" Kevin MacLeod (<a href="https://incompetech.com">incompetech.com</a>)<br>
Licensed under Creative Commons: By Attribution 4.0 License <br>
<a href="http://creativecommons.org/licenses/by/4.0/">http://creativecommons.org/licenses/by/4.0/</a></span></li>
	</ul>

	<h2>Third-party libraries</h2>

	<p>ByeCorps ID relies on the following third-party libraries:</p>
	<ul>
		<li><code>sentry/sdk</code> for diagnostics.</li>
		<li><code>phpmailer/phpmailer</code> for email.</li>
		<li><code>erusev/parsedown</code> and <code>erusev/parsedown-extra</code> for parsing Markdown right in PHP.</li>
		<li><code>kornrunner/blurhash</code> for generating blurhashes.</li>
	</ul>

	<p>Getting the FPS of your display powered by <a href="https://stackoverflow.com/a/5111475">this StackOverflow answer</a>.</p>
	<a id="final"></a>

	<p>Thank you for using ByeCorps ID.</p>
</div>

<script>
	//autoscroll

	const credits = document.getElementById("credits");
	const finalLine = document.getElementById("final");
	credits.scrollTop = 0;
	let fakeScrollTop = 1;
	let speed = 15 // pixels per second
	// speed = 100

	const music = new Audio("https://cdn.byecorps.com/id/music/Screen Saver.mp3");
	music.loop = true;
	const silence = new Audio("https://cdn.byecorps.com/id/music/500-milliseconds-of-silence.mp3");

	var filterStrength = 60;
	var frameTime = 0, lastLoop = new Date, thisLoop;

	let fps = 60;

	setInterval(function(){
		document.getElementById("fps").innerText = (1000/frameTime).toFixed(0) + ` / Speed ${speed}`;
		fps = 1000/frameTime;
	},1000);

	function setFPS() {
		var thisFrameTime = (thisLoop=new Date) - lastLoop;
		frameTime+= (thisFrameTime - frameTime) / filterStrength;
		lastLoop = thisLoop;

		requestAnimationFrame(setFPS);
	}


	function main() {
		let frameFraction = 1 / fps;
		fakeScrollTop += speed * frameFraction;
		credits.scrollTop = Math.floor(fakeScrollTop);

		if (credits.scrollTop >= credits.scrollTopMax) {
			music.loop = false;
			setTimeout(function () {
				credits.style.overflowY = "auto";
				credits.scrollTo(0, 0);
				document.getElementsByClassName("spacer")[0].style.height = 0;
			}, 2500);
		} else {
			requestAnimationFrame(main);
		}

	}

	function startMain() {
		document.getElementById("start").style.display = "none";
		music.play();
		setTimeout(function () {
			requestAnimationFrame(main);
		}, 500);
	}

	async function checkForAutoplay() {
		try {
			await silence.play();
			// so if that works, just start the credits.
			document.getElementById("start").style.display = "none";

			startMain();
		} catch (e) {
			document.getElementById("start").style.display = "block";

			console.error(e);
		}
	}

	document.getElementById("start").onclick = startMain;

	requestAnimationFrame(setFPS);

	checkForAutoplay();








</script>
