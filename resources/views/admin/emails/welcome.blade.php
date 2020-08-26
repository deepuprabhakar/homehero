<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Home Hero Admin Account</title>
	
	</head>
	<style>
		body{
			font-family: 'Lato', sans-serif;
		}
	</style>
	<body>
		<table style="max-width:700px; margin:0 auto; font-family: arial; font-size: 12px; line-height: 20px; background-color: #f4f4f4; padding:0px;" cellpadding="0" cellspacing="0">
		<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr style="background-color: #ffd800;">
					<td colspan="3">
						<table width="100%">
							<tr>
								<td valign="top" style="padding:30px 20px;" align="center">
									{{ Html::image('/public/homehero.png', 'Home Hero Logo', ['style' => 'width: 240px;']) }}
								</td>
								<td align="left" width="70%">
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
					<td valign="top" colspan="2" style="padding:0px 20px;">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" style="font-weight: bold;">From</td>
								<td valign="top">
									<P style="margin:0px;">The Home Hero<br>
										267-291-Hero<br>
										Sidekick@thehomehero.com<br>
										www.thehomehero.com<br>
										306 Fulton Street<br>
									Philadelphia, PA 19147</P>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top" style="padding:0px;">
						
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding:20px;">
						<table width="100%" cellpadding="0" cellspacing="0" >
								<tbody>
									<tr>
										<td>
											<p>
												Hi,
											</p>
											<p>
												Your account has been created. Please use the following credentials to login.
											</p>
											<p>
												<a href="{{ $data['actionUrl'] }}">Click Here</a>
											</p>
											<p>
												Username: {{ $data['username'] }}
											</p>
											<p>
												Password: {{ $data['password'] }}
											</p>
										</td>
									</tr>
									<tr>
								</tbody>
						</table>
					</td>
				</tr>
				<tr>
					
				</tr>
			</table>
			</td>
			</tr>
		</table>
	</body>
</html>
