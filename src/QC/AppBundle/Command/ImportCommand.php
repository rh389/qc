<?php
namespace QC\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use QC\AppBundle\Entity\Customer;
use QC\AppBundle\Entity\CustomerContact;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('qc:import')
            ->setDescription('Import data from the Expert Systems MDB')
            ->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Specify the path of the MDB'
        )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        $command = $this->getApplication()->find('doctrine:schema:drop');
        if($command->run(new ArrayInput(array('--force'=>true, '')), $output) == 0) {
            $output->writeln('Schema successfully dropped');
        }

        $command = $this->getApplication()->find('doctrine:schema:create');
        if($command->run(new ArrayInput(array('')), $output) == 0) {
            $output->writeln('Schema successfully created');
        }

        if(file_exists($path)){
            try{
                $connection = new \COM("ADODB.Connection")  or die("Unable to instantiate ADODB driver.");
                $connection->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $path . ";");
                $resultSet = $connection->Execute("
                SELECT
                    [ID],
                    [Company Name],
                    [Address1],
                    [Address2],
                    [Address3],
                    [Address4],
                    [Post Code],
                    [Note]
                FROM [Customers]");

                /** @var $entityManager \Doctrine\ORM\EntityManager **/
                $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();


                while (!$resultSet->EOF) {
                    $customerModel = new Customer();
                    $customerModel
                        ->setShortCode(utf8_encode($resultSet->Fields(0)->value))
                        ->setName(utf8_encode($resultSet->Fields(1)->value))
                        ->setAddress1(utf8_encode($resultSet->Fields(2)->value))
                        ->setAddress2(utf8_encode($resultSet->Fields(3)->value))
                        ->setAddress3(utf8_encode($resultSet->Fields(4)->value))
                        ->setAddress4(utf8_encode($resultSet->Fields(5)->value))
                        ->setPostcode(utf8_encode($resultSet->Fields(6)->value))
                        ->setNote(utf8_encode($resultSet->Fields(7)->value));

                    $entityManager->persist($customerModel);
                    $resultSet->MoveNext();
                }
                $entityManager->flush();

                $resultSet = $connection->Execute("
                SELECT
                    [ID],
                    [Contact Name],
                    [Position],
                    [Tel],
                    [Fax],
                    [Email],
                    [Mobile]
                FROM [Customer Contacts]");

                while (!$resultSet->EOF) {
                    $customerContactModel = new CustomerContact();
                    $customerContactModel
                        ->setName(utf8_encode($resultSet->Fields(1)->value))
                        ->setPosition(utf8_encode($resultSet->Fields(2)->value))
                        ->setTelephone(utf8_encode($resultSet->Fields(3)->value))
                        ->setFax(utf8_encode($resultSet->Fields(4)->value))
                        ->setEmail(utf8_encode($resultSet->Fields(5)->value))
                        ->setMobile(utf8_encode($resultSet->Fields(6)->value));

                    $customer = $entityManager->getRepository('QCAppBundle:Customer')->findOneByShortCode($resultSet->Fields(0)->value);
                    $customerContactModel->setCustomer($customer);
                    $entityManager->persist($customerContactModel);
                    $resultSet->MoveNext();
                }

                $entityManager->flush();

                $connection->Close();
            }
            catch(\Exception $e){
                $output->writeln("Error importing data: ".$e->getMessage());
            }
        }
        else{
            $output->writeln("File '$path' does not exist");
        }
    }
}