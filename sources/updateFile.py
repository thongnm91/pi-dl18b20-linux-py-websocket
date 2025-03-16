import paramiko

# SFTP Server details
host = "shell.vamk.fi"
port = 22  # Default SFTP port
username = ""
password = ""

# Local and remote file paths
local_file = "/sys/bus/w1/devices/28-00000b994082/w1_slave"
remote_file = "public_html/projectEmbeddedLinux/dev_ds18b20.txt"

try:
    # Establish SSH connection
    transport = paramiko.Transport((host, port))
    transport.connect(username=username, password=password)

    # Open SFTP session
    sftp = paramiko.SFTPClient.from_transport(transport)

    # Overwrite the file on the server
    sftp.put(local_file, remote_file)

    print(f"Successfully uploaded {local_file} to {remote_file}")

    # Close connections
    sftp.close()
    transport.close()

except Exception as e:
    print(f"Error: {e}")
